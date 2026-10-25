<?php

namespace App\AppModules\TrackedUrlUpdate;

use Carbon\Carbon;
use Exception;
use Intervention\Image\Facades\Image;
use App\AppModules\Core\Util;
use App\AppModules\Logging\Log;
use App\Model\TrackedUrl;
use App\Model\TrackedUrlImage;

class TrackedUrlImageDownloader {
    const MAX_UPDATE_ATTEMPTS = 3;
    private $scriptExecutor;
    
    public function __construct(TrackedUrlScriptExecutor $scriptExecutor) {
        $this->scriptExecutor = $scriptExecutor;
    }

    public function downloadImages(array $urls): void {
        $this->deleteTempImageFiles();
        
        try {
            $this->downloadImagesInternal($urls);
        } finally {
            $this->deleteTempImageFiles();
        }
    }
    
    public function downloadImagesInternal(array $urls): void {
        $updateTime = Carbon::now();
        $scriptFailed = true;
        $successCount = 0;
        $failCount = 0;
        
        try {
            $this->scriptExecutor->runScript();
            $scriptFailed = false;
        } catch (Exception $e) {
            Log::debug('Tracked URL update script failed');
            Log::debug(Util::makeExceptionMessage($e));
            $failCount = count($urls);
        }
        
        if (!$scriptFailed) {
            foreach ($urls as $url) {
                try {
                    if ($this->createTrackedUrlImage($url, $updateTime)) {
                        $successCount++;
                    } else {
                        $failCount++;
                    }
                } catch (Exception $e) {
                    Log::debug('Image entry create failed');
                    Log::debug(Util::makeExceptionMessage($e));
                    $failCount++;
                }
            }
        }
        
        $updateAttempts = count($urls);
        Log::debug("Update attempts: $updateAttempts, Succeeded: $successCount, Failed: $failCount");
    }
    
    private function createTrackedUrlImage(TrackedUrl $url, Carbon $updateTime): bool {
        $success = false;        
        $tempImagePath = storage_path('temp/tracked-url-images/' . $url->id . '.jpg');
        
        try {
            if (file_exists($tempImagePath)) {
                $this->createImageEntry($url, $tempImagePath);
                $success = true;
            }
        } catch (Exception $e) {
            Log::debug('Unable to make image entry');
            Log::debug(Util::makeExceptionMessage($e));
        }
        
        $url->last_updated_at = $updateTime;
                
        if ($success) {
            $url->last_update_result = 'success';
            $url->is_updating = 0;
            $url->update_attempts = 0;
        } else {
            $url->last_update_result = 'fail';
            $url->update_attempts++;
            
            if ($url->update_attempts >= self::MAX_UPDATE_ATTEMPTS) {
                $url->is_updating = 0;
                $url->update_attempts = 0;
            }
        }
        
        $url->save();
        return $success;
    }
    
    private function createImageEntry(TrackedUrl $url, string $tempImagePath): void {
        $imageFileName = bin2hex(random_bytes(48));
        $imageFileName = chunk_split($imageFileName, 16, '-');
        $imageFileName = substr($imageFileName, 0, strlen($imageFileName) - 1);
        
        $image = new TrackedUrlImage();
        $image->tracked_url_id = $url->id;
        $image->image_path = 'user-' . $url->created_by;
        $image->image_filename = $imageFileName;
        
        $saveDir = public_path('assets/tracked-url-images/' . $image->image_path);
        
        try {
            if (!file_exists($saveDir)) {
                if (!mkdir($saveDir)) {
                    throw new Exception('Error creating image save directory: ' . $saveDir);
                }
            }

            $imagePath = $image->getImagePath();
            $tnPath = $image->getThumbnailPath();

            if (file_exists($imagePath) || file_exists($tnPath)) {
                throw new Exception('Error - image or thumbnail file already exist');
            }

            if (!copy($tempImagePath, $imagePath)) {
                throw new Exception('Error saving image file: ' . $imagePath);
            }

            $thumbnail = Image::make($imagePath);

            $thumbnail->resize(640, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($tnPath);

            if (!file_exists($tnPath)) {
                throw new Exception('Error saving image thumbnail: ' . $tnPath);
            }
            
            $image->save();
            
        } catch (Exception $e) {
            Log::debug('Unable to copy tracked URL image files:');
            Log::debug(Util::makeExceptionMessage($e));
            $this->deleteFailedImage($image);
            throw $e;
        }
    }
    
    private function deleteFailedImage(TrackedUrlImage $image): void {
        Log::debug('Deleting failed image data');
        
        $imagePath = $image->getImagePath();
        $tnPath = $image->getThumbnailPath();
            
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        if (file_exists($tnPath)) {
            unlink($tnPath);
        }
    }
    
    private function deleteTempImageFiles(): void {
        $files = glob(storage_path('temp/tracked-url-images/*'));
        
        foreach($files as $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
    }
}

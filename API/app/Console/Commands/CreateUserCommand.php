<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AppModules\Users\UserUpdater;

class CreateUserCommand extends Command {
    protected $signature = 'create-user {email} {password} {role}';
    protected $description = 'Creates a new user entity with the specified email address, password and role (member|admin).';
    private $userUpdater;
    
    public function __construct(UserUpdater $userUpdater) {
        parent::__construct();
        $this->userUpdater = $userUpdater;
    }
    
    public function handle(): void {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $role = $this->argument('role');
        
        $userData = ['email' => $email, 'password' => $password, 'role' => $role];
        $result = $this->userUpdater->updateUser($userData);
        
        if ($result['success']) {
            $this->info('Successfully created user ' . $email);
        } else {
            $this->error('Unable to create user:');
            foreach ($result['errors'] as $error) {
                $this->error($error[0]);
            }
        }
    }
}

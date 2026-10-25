const fs = require('fs')
const puppeteer = require('puppeteer');

async function main() {
    console.log('Started update tracked URL script');
    await readUrlsAndUpdate();
}

async function readUrlsAndUpdate() {
    console.log('Reading tracked URLs file');
    await fs.readFile('./storage/temp/urls-to-update.json', 'utf8', async (err, data) => {
        if (err) {
            throw new Error('Unable to open tracked URLs file - ' + err);
        } else {
            urls = JSON.parse(data);
            console.log('Found ' + urls.length + ' URLs to update');

            if (urls.length > 0) {
                await updateUrls(urls);
            }
        }
    });
}

async function updateUrls(urls) {
    let jobs = null;
    const batchSize = 15;
    const browser = await puppeteer.launch({ 
            defaultViewport: { width: 1920, height: 1080 }, 
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox'
            ] 
        });

    for (let i = 0; i < urls.length; i += batchSize) {
        jobs = [];

        for (let j = i; j < (i + batchSize) && j < urls.length; j++) {
            const url = urls[j];
            jobs.push(getScreenshot(browser, url.id, url.url));
        }

        await Promise.all(jobs);
    }

    await browser.close();
    console.log('Update completed');
}

async function getScreenshot(browser, urlId, url) {
    console.log('Visiting URL ' + urlId + ': ' + url);
    
    const page = await browser.newPage();
    await page.setDefaultNavigationTimeout(12 * 1000);
    await page.setUserAgent('Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Mobile Safari/537.36');

    try {
        await page.goto(url);
        const screenshotPath = './storage/temp/tracked-url-images/' + urlId + '.jpg';
        await page.screenshot({ path: screenshotPath, type: 'jpeg', quality: 92, fullPage: true });
        console.log('Saved screenshot ' + urlId + '.jpg');
    } catch (err) {
        console.log('Unable to update URL ' + urlId + ' - ' + err);
    }

    await page.close();
}

process.on('unhandledRejection', err => {
    console.error(err.message);
    process.exit(1);
});

main();

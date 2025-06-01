<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url as TagsUrl;
use Spatie\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // modify this to your own needs
        // SitemapGenerator::create(config('app.url'))
        //     ->writeToFile(public_path('sitemap.xml'));
       /*  $sitemap = Sitemap::create()
            ->add(Url::create('/home'))
            ->add(Url::create('/contact'))
            ->add(Url::create('/about'))
            ->add(Url::create('/pricing'));

            $sitemap->writeToFile(public_path('sitemap.xml'));
 */


        SitemapGenerator::create(config('app.url'))
        ->getSitemap()
        ->writeToFile(public_path('sitemap.xml'));   
            // ->add(Url::create('/contact'));
    }
}

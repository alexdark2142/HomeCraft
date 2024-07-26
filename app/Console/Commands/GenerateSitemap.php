<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate a sitemap.xml file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency('daily')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/liquor-gift-box')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/icon-and-the-crosses')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/flower-pots')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/flower-pots/small')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/flower-pots/medium')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/flower-pots/large')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/cat-and-dog-food-bowl-stand')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/cat-and-dog-food-bowl-stand/small-177')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/cat-and-dog-food-bowl-stand/medium-400')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/entertainment')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/products/other-products')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
            ->add(Url::create('/product')
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate(now()))
        ;

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}

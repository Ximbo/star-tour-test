<?php

use Illuminate\Database\Seeder;

/**
 * Class UrlSeeder
 */
class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Url::class, 10)->create()->each(function($u) {
            /** @var \App\Url $u */
            factory(App\Xpath::class, 5)->create(['url_id' => $u->id])->each(function($x) {
                /** @var \App\Xpath $x */
                factory(App\Content::class, 5)->create(['xpath_id' => $x->id]);
            });
        });
    }
}

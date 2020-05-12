<?php

namespace Tests\Feature;

use App\Article;
use App\Interest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function it_has_a_title_and_text()
    {
        $article = Article::create(['title' => 'Laravel', 'text' => 'great']);

        $this->assertEquals('Laravel', $article->title);

        $this->assertEquals('great', $article->text);
    }

    /** @test */
    public function an_article_can_append_to_a_interest()
    {
        $interest = Interest::create(['text' => 'coding']);

        $article = Article::create(['title' => 'foo', 'text' => 'bar']);
        $article->interests()->attach($interest->id);

        $this->assertTrue((bool) $article->interests()->whereId($interest->id));
    }
}

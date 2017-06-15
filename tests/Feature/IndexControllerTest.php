<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexControllerTest extends TestCase
{
    /**
     * 测试首页
     *
     * @return void
     */
    public function testindex()
    {
        $response = $this->get('/');
        $response->assertSee('上传');
        $response->assertStatus(200);
    }

    /**
     * 测试图片上传
     *
     * @return void
     */
    public function testUploadContent()
    {
        $response = $this->json('POST', '/index/upload', [
            //模拟图片上传
            'upload_img' => UploadedFile::fake()->image('/imgs/1.png')
        ]);
        $response
            //断言该响应具有给定的状态码。
            ->assertStatus(200)
            //断言该响应包含指定 JSON 片段。
            ->assertJsonFragment([
                'http_code' => 2000,
            ]);
    }
}

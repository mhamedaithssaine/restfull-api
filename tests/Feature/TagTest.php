<?php

test('can list tags', function () {
    $response = $this->get('api/v1/tags');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        "data"=>[
            "*"=>[
                'name',
            ],
        ],
    ]);
});

test("can add tag",function(){
$tag=[
    "name"=>"yara"
];
$response = $this->post("api/v1/tags",$tag);
$response->assertStatus(201);
$tag=$response->json('data');

$this->assertDatabaseHas('tags',['nmae'=>$tag['name']]);
$response->$this->delete("api",['id'=>])


});
<?php
define('BASE_DIR', dirname(__DIR__));

require BASE_DIR . '/vendor/autoload.php';

$chapters = json_decode(file_get_contents(BASE_DIR.'/resource/chapters-id.json'));

$ayatIncr = 0;
$surat = 2;
$ayat = 255;

foreach($chapters->chapters as $key => $chapter){
    $idStart = $chapters->chapters[$key]->id_start = $ayatIncr;

    $ayatid = $idStart + $ayat;

    if($chapter->chapter_number == $surat)
        break;

    $ayatIncr = $ayatIncr + $chapter->verses_count;
}


$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://quran.com/api/api/v3/'
]);

// Search a chapter

$res = $client->request('GET', 'chapters/'.$surat.'/verses/'.$ayatid, [
    'query' => ['language' => 'id', 'translations' => 33]
]);
if($res->getStatusCode() == 200){
    $json = json_decode($res->getBody());

    echo '<p>'.$json->verse->text_madani."<p>";
    foreach($json->verse->translations as $translation) {
        if($translation->resource_id == 33){
            echo $translation->text."\n";
            break;
        }
    }
}

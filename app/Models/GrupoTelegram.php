<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoTelegram extends Model
{
    use HasFactory;
    protected $table = 'grupos_telegram';

    protected $fillable = ['nome', 'chatid', 'apelido'];

    public function getSapBlogPosts(GrupoTelegram $grupo)
    {
        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");
        $url = sprintf("https://blogs.sap.com/%04d/%02d/%02d/", $ano, $mes, $dia);
        $today = sprintf("%02d/%02d/%04d", $dia, $mes, $ano);

        $conteudo = file_get_contents($url);
        $documento = new \DOMDocument();
        $documento->loadHTML($conteudo, LIBXML_NOERROR);
        $xpaht = new \DOMXPath($documento);
        $posts = $xpaht->query('.//div[@class="dm-contentListItem__body"]//div[@class="dm-contentListItem__title"]//a/@href');

        if (!empty($posts)) {
            self::processPostsToSend($posts, $today, $grupo);
        }
    }

    private function processPostsToSend($posts, $today, $grupo)
    {
        self::sendMessageTelegramGroups("SAP Community Posts - $today", $grupo);
        foreach ($posts as $post) {
            $message = $post->textContent;
            self::sendMessageTelegramGroups($message, $grupo);
        }
    }

    private function sendMessageTelegramGroups($message, $grupo)
    {
        $data = ['text' => $message, 'chat_id' => $grupo->chatid];
        file_get_contents("https://api.telegram.org/bot2008051556:AAFz2wjLdYfI13WUuSRYiXd12zimF-ihLIE/sendMessage?" . http_build_query($data));
        sleep(1);
    }

    private function isNullOrEmptyString($str)
    {
        return (!isset($str) || trim($str) === '');
    }
}

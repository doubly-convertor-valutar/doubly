<?php 

namespace Grizzlyapps\Shopify;

class Shopify
{
    //check if application is in test mode
    //verify by user id
    public function getIsTest($userId) {
        if (in_array($userId, array(1))) {
            return true;
        }
        return false;
    }

    public function addAsset($_key, $_path, $_force = false) {
        $theme = $this->call(['URL' => 'themes.json', 'RETURNARRAY' => true, 'DATA' => ['role'=>'main', 'fields'=>'id']]);
        $themeId = $theme['themes'][0]['id'];
        $assets = $this->call(['URL' => 'themes/'.$themeId.'/assets.json', 'RETURNARRAY' => true, 'DATA' => ['fields'=>'key']]);
        $assetKeys = array();
        foreach ($assets['assets'] as $asset) {
            $assetKeys[$asset['key']] = true;
        }
        
        if (!isset($assetKeys[$_key]) || $_force) {
            $this->call(['URL' => 'themes/'.$themeId.'/assets.json', 'METHOD' => 'PUT', 'DATA' => ['asset' => ['key'=>$_key,'src'=>$_path]]]);           
        }
    }

    public function removeAsset($_key) {
        $theme = $this->call(['URL' => 'themes.json', 'RETURNARRAY' => true, 'DATA' => ['role'=>'main', 'fields'=>'id']]);
        $themeId = $theme['themes'][0]['id'];
        $assets = $this->call(['URL' => 'themes/'.$themeId.'/assets.json', 'RETURNARRAY' => true, 'DATA' => ['fields'=>'key']]);
        $assetKeys = array();
        foreach ($assets['assets'] as $asset) {
            if ($asset['key']==$_key) {
                $this->call(['URL' => 'themes/'.$themeId.'/assets.json?asset[key]='.$_key, 'METHOD' => 'DELETE']);
            }
        }
    }

    public function addScriptTag($_url) {
        $data = $this->call(['URL' => 'script_tags.json?src='.urlencode($_url), 'RETURNARRAY' => true]);

        if (count($data['script_tags'])==0) {
            $this->call(['URL' => 'script_tags.json', 'METHOD' => 'POST', 'DATA' => ['script_tag' => ['event'=> 'onload', 'src'=>$_url]]]);           
        }
    }

    public function removeScriptTag($_url) {
        $data = $this->call(['URL' => 'script_tags.json', 'RETURNARRAY' => true]);

        foreach ($data['script_tags'] as $script_tag) {
            if (isset($script_tag['src']) && $script_tag['src']==$_url) {
                $this->call(['URL' => 'script_tags/'.$script_tag['id'].'.json', 'METHOD' => 'DELETE']);
            }
        }
    }

    public function addWebhook($_topic, $_address) {
        $webhooks = $this->call(['URL' => 'webhooks.json', 'RETURNARRAY' => true, 'DATA' => ['fields'=>'address']]);
        $webhookKeys = array();
        foreach ($webhooks['webhooks'] as $webhook) {
            $webhookKeys[$webhook['address']] = true;
        }
        if (!isset($webhookKeys[$_address])) {
            $this->call(['URL' => 'webhooks.json', 'METHOD' => 'POST', 'DATA' => ['webhook' => ['topic' => $_topic, 'address' => $_address]]]);           
        }
    }  

    public function removeWebhook($_address) {
        $webhooks = $this->call(['URL' => 'webhooks.json', 'RETURNARRAY' => true, 'DATA' => ['fields'=>'id,address']]);
        foreach ($webhooks['webhooks'] as $webhook) {
            if (isset($webhook['address']) && $webhook['address']==$_address) {
                $this->call(['URL' => 'webhooks/'.$webhook['id'].'.json', 'METHOD' => 'DELETE']);     
            }
        }
    }    
    
    //verify if webhook request is valid (like uninstall webhook)
    public function verifyWebhook($data, $hmac_header) {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, env('APP_CLIENT_SECRET'), true));
        return ($hmac_header == $calculated_hmac);
    }

    public function checkCallLimit($_headers, $_sleepOn)
    {
        if (isset($_headers['HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT'])) {
            $params = explode('/', $_headers['HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT']);
            $callsLeft = (int)$params[1] - (int)$params[0];
                
            if ($callsLeft<=$_sleepOn) {
                sleep((40-$callsLeft/2));
                \Log::error('Reached call limit '.$callsLeft);
            }
        }
    }

}

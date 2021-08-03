使用方法
========

1.使用composer（推荐）
--------------------
composer config secure-http false && composer require lunbor/openplatform
```php
use Gd\Sdk;

require __DIR__ . "/../autoload.php";

$obj = new Sdk("testappkey111", "请更正为自己的appsecret", "test", "1.0.0");

$str = <<<str
{
  "buyer_title": "sdfsdfsdf",
  "invoice_type_code":"032",
  "title_type": 1,
  "order_id":"13332311x1asdfcsdcc",
  "buyer_taxpayer_num": "123456789406426",
  "buyer_address": "广东 深圳 南山",
  "buyer_bank_name": "中国工商银行",
  "buyer_bank_account": "621281240200099900000",
  "buyer_phone": "0755—26951098",
  "buyer_email": "Calvin.chen@gaopeng.com",
  "user_openid": "201708022e87777ggg74",
  "channel": "GP110001",
  "machine_no":"0",
  "seller_taxpayer_num":"111112222233333",
  "callback_url":"http://test.feehi.com/sign/mock/invoice-callback.php",
  "tax_amount":864,
  "amount_has_tax":9508,
  "amount_without_tax":8644,
  "drawer":"ddd",
  "items": [
    {
      "name": "商品1",
      "tax_rate": 100,
      "models": "XYZ",
      "unit": "个",
      "total_price": 8644,
      "price":"17.288",
      "tax_amount":864,
      "total": "5",
      "include_tax_flag":"1",
      "tax_code": "1020202000000000000",
      "discount":0,
      "tax_type":"1",
      "preferential_policy_flag":"0"
    }
  ]

}
str;

$post = json_decode($str, true);
$result = $obj->httpRequest("/invoice/blue", $post);
var_dump($result);
print_r($obj->getLog());
```

2.手动加载
-----------
下载源码到目录，假设放置在/path/to/golden目录中
```php
use Gd\Sdk;

require "/path/to/golden/autoload.php";

$obj = new Sdk("testappkey111", "请更正为自己的appsecret", "test", "1.0.0");
```

运行example
==========
```bash
    composer run-script example
```
<?php

namespace App\Controllers;

class ShowCtrl
{
    /**
     * @param array<string> $params
     * @return string
     */
    public static function page(array $params = []): string
    {
        $count = array_key_exists('count', $params) ? $params['count'] : 10;
        $protocol = 'http';
        if (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] === 'on') {
            $protocol = 'https';
        }
        return '<h1>Quotes</h1><div id="quotes"></div>
<script>
  const domain = "' . $_SERVER['SERVER_NAME'] . '";
  const protocol = "' . $protocol . '";
  const count = ' . $count . ';' .
        <<<EOT
  async function postData(url = '', count = 1) {
  const input = '{"query":"query{page(data:' + count + '){author year text}}"}'
  const response = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: input
  });
  return await response.json();
}

// Up to 10 quotes are defined in db/quotes.json
postData(protocol + '://' + domain + '/quotes', count)
  .then((data) => {
    let text = '';
    data.data.page[0].forEach(i => {
        text += '<tr><td>' + i.author + '</td><td>' + i.year + '</td><td>' + i.text + '</td></tr>';
    });
    const node = document.createElement('table');
    node.innerHTML = text;
    document.getElementById('quotes').append(node);
  });
</script>
EOT;
    }
}

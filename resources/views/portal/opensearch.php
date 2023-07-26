<?php
$route = route('portal.groups');

echo <<<XML

<?xml version="1.0"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName>kozossegek.hu</ShortName>
    <Image width="16" height="16" type="image/x-icon">/favicon.ico</Image>
    <Url type="text/html" template="$route" />
    <Url type="application/x-suggestions+json" template="https://datacadamia.com/lib/exe/ajax.php?call=suggestions&amp;q={searchTerms}" />
</OpenSearchDescription>

XML;
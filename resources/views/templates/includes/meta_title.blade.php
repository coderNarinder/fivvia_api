<meta charset="utf-8" />
<title>{{$title ?? ' '}} | <?= $client_head ? ucfirst($client_head->company_name) : 'Royo' ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
@if(isset($category))
    @if($category->translation->first())
        <meta content="{{$category->translation->first()->meta_description}}" name="description" />
        <meta name="keywords" content="{{$category->translation->first()->meta_keywords}}">
    @endif
@else
    <meta name="keywords" content="Codiner">
@endif
<meta name="author" content="Codiner">
<meta name="msvalidate.01" content="B8687C8E8293DCA0BE3BE00D09D59667" />
<link rel="shortcut icon" href="<?= $favicon ?>">
<script>
    (function(c,l,a,r,i,t,y){
    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i+"?ref=bwt";
    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "b5mkqsbdou");
    </script>
<style>
    :root {--theme-deafult: green; }
</style>
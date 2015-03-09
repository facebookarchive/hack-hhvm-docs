<?hh
async function curl_exec_await(string $url): Awaitable<string> {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $mh = curl_multi_init();
  curl_multi_add_handle($mh, $ch);
  do {
    $active = 1;
    do {
      $status = curl_multi_exec($mh, $active);
    } while ($status == CURLM_CALL_MULTI_PERFORM);
    $select = await curl_multi_await($mh);
    if ($select == -1) break;
  } while ($status === CURLM_OK);
  $content = (string)curl_multi_getcontent($ch);
  curl_multi_remove_handle($mh, $ch);
  curl_multi_close($mh);
  return $content;
}

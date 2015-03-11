<?hh
async function search(string $q): Awaitable<string> {
  $ch = curl_init("http://example.com/");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, array('q' => $q));
  return await HH\asio\curl_exec($ch);
}

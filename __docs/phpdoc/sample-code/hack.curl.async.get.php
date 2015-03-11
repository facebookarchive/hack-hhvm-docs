<?hh
async function search(string $q): Awaitable<string> {
  return await HH\asio\curl_exec("http://example.com/?q=". urlencode($q));
}

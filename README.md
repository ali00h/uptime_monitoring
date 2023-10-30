# uptime_monitoring
This is a php app that you can monitor 10 url for availability. If any of the urls give a response code other than 200, you will be notified through the Bale messenger.

# Usage
1- Rename `.env-example` to `.env`

2- Fill `.env` with valid data:
| ENV | Description |
| --- | --- |
| `TIME_ZONE` | Your time zone |
| `BALE_TOKEN` | Your bale token |
| `BALE_CHAT_ID` | Your bale chat id |
| `URL1` | Your url for monitoring |
| `NOTIFY_WAIT_NEXT_SEND_MINUTES` | If any of urls response other than 200, system wait this minutes for next check. |    

3- Set Root Url of app to `cron job`. For example:
```
*/10 * * * * wget -O /dev/null "https://your-domain.com/" > /dev/null 2>&1
```

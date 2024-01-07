# uptime_monitoring
This is a php app that you can monitor 10 url for availability. If any of the urls give a response code other than 200, you will be notified through the Bale messenger.

# Usage
1- Rename `.env-example` to `.env`

2- Fill `.env` with valid data:
| ENV | Description |
| --- | --- |
| `TZ` | Your time zone |
| `BALE_TOKEN` | Your bale token |
| `BALE_CHAT_ID` | Your bale chat id |
| `URL1` | Your url for monitoring. you can define 10 different urls. for example URL2,URL3,... if each of urls was empty string, app will ignore it. |
| `NOTIFY_WAIT_NEXT_SEND_MINUTES` | If any of urls response other than 200, system wait this minutes for next check. |    

3- Set Root Url of app to `cron job`. For example:
```
*/10 * * * * wget -O /dev/null "https://your-domain.com/" > /dev/null 2>&1
```
This means that the checking operation happens every 10 minutes.

# Docker
You can set env to docker-compose.yml and use `docker-compose up -d` to run it or use [ali00h/uptime_monitoring](https://hub.docker.com/r/ali00h/uptime_monitoring) Docker image.

## Docker Environment Variables:

| ENV | Description |
| --- | --- |
| `TZ` | Your time zone |
| `BALE_TOKEN` | Your bale token |
| `BALE_CHAT_ID` | Your bale chat id |
| `URL1` | Your url for monitoring. you can define 10 different urls. for example URL2,URL3,... if each of urls was empty string, app will ignore it. |
| `NOTIFY_WAIT_NEXT_SEND_MINUTES` | If any of urls response other than 200, system wait this minutes for next check. |   
| `LOG_MAX_LINE_COUNT` | Each log file has a maximum of this number of lines. This is to avoid taking up too much disk space. |
| `LOGIN_USERNAME` | Login username for web reporting panel |
| `LOGIN_PASSWORD` | Login password for web reporting panel |

For checking *cronjob* log details you can use `https://yourdomain.com/login` 
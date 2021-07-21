# BigFun
比房科技面試 Project

# 試題

以 Laravel framework (不限版本) 完成下列兩項功能：
* 建立一 Job 抓取下方社會住宅 [Open API](https://data.taipei/#/dataset/detail?id=659c3565-df41-4f80-915f-95e83071bdcd) 資料並寫入/更新到資料庫。 (`/houses/sync`)
* 建立一 RESTful API 可依下列條件查詢符合的資料：
  * 位於指定行政區的社會住宅 (`/houses/search`)
  * 位於指定座標中心半徑 1000 公尺內的社會住宅 (`/houses/search/around`)
  * 規劃戶數在指定數字區間內的社會住宅，如 200 ~ 400 戶 (`/houses/search`)

# Package & Framework
* Laravel 8.51.0

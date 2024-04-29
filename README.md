
# Project Title

A brief description of what this project does and who it's for:

 RESTful API to allow search in the given inventory ,
 data from (https://api.npoint.io/dd85ed11b9d8646c5709) endpoint ;


## API Reference

#### Get all items

```http
  GET /api/test
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` |  hotel name  |
| `city` | `string` |  destination city  |
| `availability` | `string` |  range as example 10-10-2020:15-10-2020 |
| `price` | `string` |  range as example 100:200 |
| `sort` | `string` | **Not Required**. sort by name or price |

#### Get item

```http
  GET /api/test?name=vlaue1&city=value2&availability=5-10-2023:15-10-2023&price=100:200&sort=price
```


## Demo

Insert gif or link to demo


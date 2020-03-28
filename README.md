# covid-scraping-api
API de acesso a dados do Covid19



## Visão geral

Esta API dá acesso aos dados gerados pelo scraping executado no script: [covid](https://github.com/vipontes/covid-scraping).

## endpoints

```bach
/worldometers
```

### Tipo
POST

### Data
```bach
{
    "selectedDate": "yyyy-MM-dd"
}
```

### Retorno 
Um array do seguinte objeto:
```bach
 {
    "worldometers_id": "",
    "measurement_date": "",
    "country": "",
    "total_cases": "",
    "new_cases": "",
    "total_deaths": "",
    "new_deaths": "",
    "total_recovered": "",
    "active_cases": "",
    "serious_cases": "",
    "cases_per_million": "",
    "deaths_per_million": ""
  }
```

## Licença de uso

[MIT](https://choosealicense.com/licenses/mit/)
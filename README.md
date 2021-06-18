# Books API

## Overview
This solution implements a CRUD REST API for books with a local database. Additionally, an API that calls an external  service (ice and fire API) to get information about books is also implemented. All implementations are backed with tests.

## Installation:
- Clone the project: ```git clone https://github.com/atunjeafolabi/estate-intel-book-api.git```
- Create a mysql database named ```estate_intel```
- Rename ```.env.example``` to ```.env``` and fill it with the database credentials (username and password)
- From the project root directory, run `composer install`
- Run migrations ```php artisan migrate```
- Run local dev server: ```php artisan serve``` 

## Usage:
The following endpoints are available and can easily be accessed as follows: 

### Get list of books

#### Request

`GET api/v1/books`

    curl -i -H 'Accept: application/json' http://localhost:8000/api/v1/books

#### Response

    {
       "data":[
          {
             "id":1,
             "name":"A Game of Thrones",
             "isbn":"978-0553103540",
             "authors":[
                "George R. R. Martin"
             ],
             "number_of_pages":694,
             "publisher":"Bantam Books",
             "country":"United States",
             "release_date":"1996-08-01"
          },
          {
             "id":2,
             "name":"A Clash of Kings",
             "authors":[
                "George R. R. Martin"
             ],
             "number_of_pages":768,
             "publisher":"Bantam Books",
             "country":"United States",
             "release_date":"1999-02-02"
          }
       ],
      "status_code":200,
      "status":"success"
    }
    
    If no books were found, the JSON below is returned;
    
    {
       "status_code":200,
       "status":"success",
       "data":[]
    }

##### Query strings    
A book can be searched by a name, country, publisher or release date as follows:
``` 
api/v1/books?name=Essential Biology
api/v1/books?country=Nigeria
api/v1/books?publisher=Oxford Publishers
api/v1/books?release_date=1999-01-01
```

## Show one book

#### Request

`GET api/v1/books/id`

    curl -i -H 'Accept: application/json' http://localhost:8000/api/v1/books/25

#### Response

    {
       "data":{
          "id":1,
          "name":"My First Book",
          "isbn":"123-3213243567",
          "authors":[
             "John Doe"
          ],
          "number_of_pages":350,
          "publisher":"Acme Books Publishing",
          "country":"United States",
          "release_date":"2019-01-01"
       },
       "status_code":200,
      "status":"success"
    }

    
## Create a new book

#### Request

`POST api/v1/books`

    curl -X POST -H "Content-Type: application/json" -d '{
        "name": "Wonders of Astronomy",
        "isbn": "999123-3213243567",
        "authors": [
                "Galileo Galileo",
                 "Adrian"
        ],
        "number_of_pages": 350,
        "publisher": "Oxford Publishers",
        "country": "England",
        "release_date": "1975-08-01"
     }' http://localhost:8000/api/v1/books


#### Response

    {
       "data":{
          "name": "Wonders of Astronomy",
          "isbn": "999123-3213243567",
          "authors": [
                  "Galileo Galileo",
                   "Adrian"
          ],
          "number_of_pages": 350,
          "publisher": "Oxford Publishers",
          "country": "England",
          "release_date": "1975-08-01"
       },
       "status_code":201,
      "status":"success"
    }

## Update a book

#### Request

`PATCH api/vi/books/id`

    curl -X PATCH -H "Content-Type: application/json" -d '{
           "name": "Wonders of Astronomy",
           "isbn": "999123-3213243567",
           "authors": [
                    "Galileo Galileo",
                    "Adrian"
           ],
           "number_of_pages": 350,
           "publisher": "Oxford Publishers",
           "country": "England",
            "release_date": "1975-08-01"
        }' http://localhost:8000/api/v1/books/2
    
#### Response
    {
        "data": {
            "id": 2,
           "name": "Wonders of Astronomy",
           "isbn": "999123-3213243567",
           "authors": [
                "Galileo Galileo",
                "Adrian"
           ],
           "number_of_pages": 350,
           "publisher": "Oxford Publishers",
           "country": "England",
            "release_date": "1975-08-01"
        },
       "status_code":200,
       "status":"success"
    }

## Delete a book

#### Request

`DELETE api/v1/books/id`

    curl -i -H 'Accept: application/json' -X DELETE http://localhost:8000/api/v1/books/1

#### Response

    {
       "status_code":204,
       "status":"success",
       "message":"The book ‘My first book’ was deleted  successfully",
       "data":[]
    }

### Get list of books from Ice and Fire API

#### Request

`GET api/external-books`

    curl -i -H 'Accept: application/json' http://localhost:8000/api/v1/external-books

#### Response

    {
       "data":[
          {
             "id":1,
             "name":"A Game of Thrones",
             "isbn":"978-0553103540",
             "authors":[
                "George R. R. Martin"
             ],
             "number_of_pages":694,
             "publisher":"Bantam Books",
             "country":"United States",
             "release_date":"1996-08-01"
          },
          {
             "id":2,
             "name":"A Clash of Kings",
             "authors":[
                "George R. R. Martin"
             ],
             "number_of_pages":768,
             "publisher":"Bantam Books",
             "country":"United States",
             "release_date":"1999-02-02"
          }
       ],
      "status_code":200,
      "status":"success"
    }
    
    If no books were found, the JSON below is returned;
    
    {
       "status_code":200,
       "status":"success",
       "data":[]
    }
    
    Optionally, a query string may be passed with the request as follows:
    
    api/external-books?name=A Clash of Kings

### Running Test:

- Create a file ```database.sqlite``` in the database folder of the project
- ```composer test```
- To run tests and generate test coverage, ```composer test:coverage```
- Test coverage files are generated in the ```tests/coverage``` directory. The ```index.html``` file can be viewed in the browser.


---

### Tech Stack

- PHP 7
- Laravel 8 framework
- Mysql
- Sqlite (For running tests)

### Future Works
- Validation still needs to be added when creating or updating a  book.
- More refactoring can still be done on the codebase
- Seeders can be added for easily loading sample data into the database

### Issues
- Kindly let me know if any issues are encountered.


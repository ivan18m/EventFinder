# **EventFinder**
HATEOAS REST API made in Laravel. 

Find places with events within a radius from sent coordinates

# Setup

## **Docker (on Linux)**
* Setup database connection in .env file,
* Run the containers
```
cd EventFinder
docker-compose up
```

## **Migrations**
```
cd EventFinder/www
php artisan migrate
```

## **Seeding**
```
php artisan db:seed
```

# API
## **Get Places or Events within a Radius**

* **URL**

    `/api/event/radius`

    `/api/place/radius`


* **Method:**
    `POST`

* **Content-Type:** 
`application/json`

* **Data Params**

    ```
    longitude: numeric|precision=10|scale=7|min:-180|max:180,
    latitude: numeric|precision=10|scale=7|min:-85.0511288|max:85.0511288,
    radius: numeric|required|min:0|max:1000,
    unit: "km" or "mi" (OPTIONAL) default is km
    ```
* **Data Params Example**

    ```
    {
        "latitude": 12.3456789,
        "longitude": 98.7654321,
        "radius": 100
    }
    ```

* **Success Response:**

    Array of Places

    Array of Events with place and comments
----

## **Insert**

* **URL:**

    `/api/place/`

    `/api/event/`

    `/api/comment/`

* **Method:**
    `POST`

* **Content-Type:** 
    `application/json`

* **Data Params**

    Place:
    ```
    name: string|required,
    longitude: numeric|precision=10|scale=7|min:-180|max:180,
    latitude: numeric|precision=10|scale=7|min:-85.0511288|max:85.0511288
    ```

    Event:
    ```
    name: string|required,
    place: integer|required,
    duration: "HH:mm"|required,
    starts_at: "YYYY-MM-DD HH:mm"|required,
    description: string|nullable (OPTIONAL)
    ```

    Comment:
    ```
    author: string|required,
    text: string|required
    event: integer|required
    ```

* **Success Response Example:**

    `{ "status" : "success", "id" : 1 }`
----
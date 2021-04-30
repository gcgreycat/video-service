# Video Service

Based on laravel

## Development

### Build docker image for development tools (php-cli, composer)
```bash
make build-toolkit
```

### Install composer dependencies
```bash
make composer-install
```

### Run developer environment in docker-compose
```bash
make run-dev
```

### Make clean db creating and seeding with test data (when run-dev is running)
```bash
make seed-db
```

## Api
### POST /api/login
Get auth token
```json
{
  "login": "string",
  "password": "string"
}
```
Response
```json
{
  "token": "string",
  "expiration_time": "timestamp"
}
```

### POST /api/subscription/create
Create user subscription

```json
{
    "package_id": "number"
}
```

Response
```json
{
    "message": "string",
    "subscription": {
        "id": "number",
        "time_start_at": "datetime",
        "package_id": "number",
        "user_id": "number"
    }
}
```

### PUT /api/subscription/edit
Edit self subscription

```json
{
    "subscription_id": "number",
    "package_id": "number"
}
```

Response
```json
{
    "message": "string",
    "subscription": {
        "id": "number",
        "time_start_at": "datetime",
        "package_id": "number",
        "user_id": "number"
    }
}
```

### DELETE /api/subscription/delete - удаляем подписку
Delete own subscription

```json
{
    "subscription_id": "number"
}
```

Response
```json
{
  "message": "string"
}
```

### GET /api/video/list?page=1&perPage=20
Get own active videos

Response
```json
{
    "total": "number",
    "videos": [
        {
            "id": "number",
            "title": "string"
        },
      ...
    ]
}
```

## Structure:
```mysql
create table video_service.packages
(
	id bigint unsigned auto_increment
		primary key,
	title varchar(255) not null
)
collate=utf8mb4_unicode_ci;

create table video_service.users
(
	id bigint unsigned auto_increment
		primary key,
	login varchar(255) not null,
	password varchar(255) not null,
	token varchar(255) null,
	token_expired_at timestamp null,
	constraint users_login_unique
		unique (login),
	constraint users_token_unique
		unique (token)
)
collate=utf8mb4_unicode_ci;

create table video_service.subscriptions
(
	id bigint unsigned auto_increment
		primary key,
	time_start_at timestamp not null,
	package_id bigint unsigned not null,
	user_id bigint unsigned not null,
	constraint subscriptions_package_id_foreign
		foreign key (package_id) references video_service.packages (id),
	constraint subscriptions_user_id_foreign
		foreign key (user_id) references video_service.users (id)
)
collate=utf8mb4_unicode_ci;

create table video_service.videos
(
	id bigint unsigned auto_increment
		primary key,
	title varchar(255) not null,
	is_free tinyint(1) not null,
	purchase_duration int unsigned null
)
collate=utf8mb4_unicode_ci;

create table video_service.package_video
(
	package_id bigint unsigned not null,
	video_id bigint unsigned not null,
	primary key (package_id, video_id),
	constraint package_video_package_id_foreign
		foreign key (package_id) references video_service.packages (id),
	constraint package_video_video_id_foreign
		foreign key (video_id) references video_service.videos (id)
)
collate=utf8mb4_unicode_ci;
```
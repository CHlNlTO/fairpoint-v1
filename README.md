# Blog Posts API Documentation

A public REST API for accessing blog posts and their associated tags. This API provides read-only access to all blog posts without requiring authentication.

## Base URL

```
https://yourdomain.com/api/v1
```

## Endpoints

### Get All Blog Posts

Retrieve all blog posts with their associated tags.

**Endpoint:** `GET /api/v1/posts`

**Response Format:**

```json
{
    "success": true,
    "message": "Blog posts retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "How to Build Amazing Laravel Applications",
            "description": "# Introduction\n\nThis is a comprehensive guide...",
            "author": "John Doe",
            "published_date": "2025-07-15",
            "active": true,
            "order": 1,
            "created_at": "2025-07-15T10:30:00.000000Z",
            "updated_at": "2025-07-16T14:20:00.000000Z",
            "tags": [
                {
                    "id": 1,
                    "name": "Laravel",
                    "created_at": "2025-07-15T10:25:00.000000Z",
                    "updated_at": "2025-07-15T10:25:00.000000Z"
                }
            ]
        }
    ],
    "meta": {
        "total_count": 3,
        "active_count": 2,
        "inactive_count": 1
    }
}
```

### Get Single Blog Post

Retrieve a specific blog post by its ID.

**Endpoint:** `GET /api/v1/posts/{id}`

**Parameters:**

-   `id` (integer, required): The ID of the blog post

**Success Response:**

```json
{
    "success": true,
    "message": "Blog post retrieved successfully",
    "data": {
        "id": 1,
        "title": "How to Build Amazing Laravel Applications",
        "description": "# Introduction\n\nThis is a comprehensive guide...",
        "author": "John Doe",
        "published_date": "2025-07-15",
        "active": true,
        "order": 1,
        "created_at": "2025-07-15T10:30:00.000000Z",
        "updated_at": "2025-07-16T14:20:00.000000Z",
        "tags": [
            {
                "id": 1,
                "name": "Laravel",
                "created_at": "2025-07-15T10:25:00.000000Z",
                "updated_at": "2025-07-15T10:25:00.000000Z"
            }
        ]
    }
}
```

**Error Response (404 Not Found):**

```json
{
    "success": false,
    "message": "Blog post not found",
    "data": null,
    "error": {
        "code": 404,
        "type": "NOT_FOUND"
    }
}
```

## Data Fields

### Blog Post Object

| Field            | Type    | Description                                 |
| ---------------- | ------- | ------------------------------------------- |
| `id`             | integer | Unique identifier for the blog post         |
| `title`          | string  | Title of the blog post                      |
| `description`    | string  | Content of the blog post in Markdown format |
| `author`         | string  | Name of the blog post author                |
| `published_date` | string  | Publication date (YYYY-MM-DD format)        |
| `active`         | boolean | Whether the blog post is active/published   |
| `order`          | integer | Display order of the blog post              |
| `created_at`     | string  | ISO 8601 timestamp of creation              |
| `updated_at`     | string  | ISO 8601 timestamp of last update           |
| `tags`           | array   | Array of associated tag objects             |

### Tag Object

| Field        | Type    | Description                       |
| ------------ | ------- | --------------------------------- |
| `id`         | integer | Unique identifier for the tag     |
| `name`       | string  | Name of the tag                   |
| `created_at` | string  | ISO 8601 timestamp of creation    |
| `updated_at` | string  | ISO 8601 timestamp of last update |

### Meta Object (Collection Only)

| Field            | Type    | Description                   |
| ---------------- | ------- | ----------------------------- |
| `total_count`    | integer | Total number of blog posts    |
| `active_count`   | integer | Number of active blog posts   |
| `inactive_count` | integer | Number of inactive blog posts |

## Example Usage

### cURL Examples

**Get all blog posts:**

```bash
curl -X GET "https://yourdomain.com/api/v1/posts" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json"
```

**Get specific blog post:**

```bash
curl -X GET "https://yourdomain.com/api/v1/posts/1" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json"
```

### JavaScript/Fetch Examples

**Get all blog posts:**

```javascript
fetch("https://yourdomain.com/api/v1/posts", {
    method: "GET",
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
})
    .then((response) => response.json())
    .then((data) => {
        console.log("Blog posts:", data.data);
        console.log("Total posts:", data.meta.total_count);
    })
    .catch((error) => console.error("Error:", error));
```

**Get specific blog post:**

```javascript
const postId = 1;
fetch(`https://yourdomain.com/api/v1/posts/${postId}`, {
    method: "GET",
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
})
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            console.log("Blog post:", data.data);
        } else {
            console.error("Error:", data.message);
        }
    })
    .catch((error) => console.error("Error:", error));
```

### Python Examples

**Using requests library:**

```python
import requests

# Get all blog posts
response = requests.get('https://yourdomain.com/api/v1/posts')
data = response.json()

if data['success']:
    blog_posts = data['data']
    print(f"Found {data['meta']['total_count']} blog posts")
    for post in blog_posts:
        print(f"- {post['title']} by {post['author']}")
else:
    print(f"Error: {data['message']}")

# Get specific blog post
post_id = 1
response = requests.get(f'https://yourdomain.com/api/v1/posts/{post_id}')
data = response.json()

if data['success']:
    post = data['data']
    print(f"Title: {post['title']}")
    print(f"Author: {post['author']}")
    print(f"Tags: {', '.join([tag['name'] for tag in post['tags']])}")
else:
    print(f"Error: {data['message']}")
```

## HTTP Status Codes

| Code  | Description                                         |
| ----- | --------------------------------------------------- |
| `200` | OK - Request successful                             |
| `404` | Not Found - Blog post with specified ID not found   |
| `500` | Internal Server Error - Server encountered an error |

## Content Types

-   **Request:** `application/json`
-   **Response:** `application/json`

## Authentication

This API is **public** and does not require authentication. All endpoints are accessible without any API keys or tokens.

## Rate Limiting

Currently, there are no rate limits implemented on this API.

## Markdown Content

The `description` field contains Markdown-formatted content. When displaying this content in your application, you'll need to parse it using a Markdown parser appropriate for your platform:

-   **JavaScript:** marked, markdown-it, or remark
-   **Python:** markdown or mistune
-   **PHP:** parsedown or league/commonmark
-   **Other languages:** Check for Markdown parsing libraries

## Error Handling

All endpoints return consistent error response format:

```json
{
    "success": false,
    "message": "Error description",
    "data": null,
    "error": {
        "code": 404,
        "type": "ERROR_TYPE"
    }
}
```

## Support

For questions or issues with this API, please contact the development team or create an issue in the project repository.

## Changelog

### Version 1.0.0

-   Initial release
-   Added GET /posts endpoint
-   Added GET /posts/{id} endpoint
-   Support for blog post tags
-   Public access (no authentication required)

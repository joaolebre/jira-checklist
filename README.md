### Jira Checklist

API that covers all the actions needed to create a checklist of tasks that need to be completed,
so that the ticket can be considered as validated. Built with the Slim framework. 

Register and login to get authorization token!

Link:
https://sandbox.exads.rocks/api

To seed the database run:

```bash
php database/dbseeder.php
```

### ENDPOINTS:

#### DOCS:

- View API Documents: `/api/docs`

#### USERS:

- List Users: `GET /api/users`

- Get User: `GET /api/users/{id}`

- Register User: `POST /api/users/register`

- Login User: `POST /api/users/login`

- Update User: `PUT /api/users/{id}`

- Update User Password: `PATCH /api/users/password/{id}`

- Update User Role: `PATCH /api/users/role/{id}`

- Delete User: `DELETE /api/users/{id}`


#### TICKETS:

- List Tickets: `GET /api/tickets`

- Get Ticket: `GET /api/tickets/{id}`

- Create Ticket: `POST /api/tickets`

- Update Ticket: `PUT /api/tickets/{id}`

- Delete Ticket: `DELETE /api/tickets/{id}`


#### TABS:

- List Tabs: `GET /api/tabs`

- Get Tab: `GET /api/tabs/{id}`

- Create Tab: `POST /api/tabs`

- Update Tab: `PUT /api/tabs/{id}`

- Update Tab Ticket: `PATCH /api/tabs/ticket/{id}` 

- Delete Tab: `DELETE /api/tabs/{id}`


#### SECTIONS:

- List Sections: `GET /api/sections`

- Get Section: `GET /api/sections/{id}`

- Create Section: `POST /api/sections`

- Update Section: `PUT /api/sections/{id}`

- Update Section Tab: `PATCH /api/sections/tab/{id}`

- Delete Section: `DELETE /api/sections/{id}`


#### ITEMS:

- List Items: `GET /api/items`

- Get Item: `GET /api/items/{id}`

- Create Item: `POST /api/items`

- Update Item: `PUT /api/items/{id}`

- Update Item Section: `PATCH /api/items/section/{id}`

- Delete Item: `DELETE /api/items/{id}`


#### ITEM STATUSES:

- List Item Statuses: `GET /api/item-statuses`

- Get Item Status: `GET /api/items-statuses/{id}`

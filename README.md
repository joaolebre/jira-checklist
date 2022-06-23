### Jira Checklist

API that covers all the actions needed to create a checklist of tasks that need to be completed,
so that the ticket can be considered as validated. Built with the Slim framework.

### ENDPOINTS:

#### DOCS:

- Documentation: `GET /`


#### USERS:

- Login User: `POST /login`

- Create User: `POST /api/users`

- Update User: `PUT /api/users/{id}`

- Delete User: `DELETE /api/users/{id}`


#### TICKETS:

- Get All Tickets: `GET /api/tickets`

- Get Ticket: `GET /api/tickets/{id}`

- Create Ticket: `POST /api/tickets`

- Update Ticket: `PUT /api/tickets/{id}`

- Delete Ticket: `DELETE /api/tickets/{id}`


#### TABS:

- Get All Tabs: `GET /api/tabs`

- Get Tab: `GET /api/tabs/{id}`

- Create Tab: `POST /api/tabs`

- Update Note: `PUT /api/tabs/{id}`

- Delete Note: `DELETE /api/tabs/{id}`


#### SECTIONS:

- Get All Sections: `GET /api/sections`

- Get Section: `GET /api/sections/{id}`

- Create Section: `POST /api/sections`

- Update Section: `PUT /api/sections/{id}`

- Delete Section: `DELETE /api/sections/{id}`


#### ITEMS:

- Get All Items: `GET /api/notes`

- Get Item: `GET /api/notes/{id}`

- Create Item: `POST /api/notes`

- Update Item: `PUT /api/notes/{id}`

- Delete Item: `DELETE /api/notes/{id}`

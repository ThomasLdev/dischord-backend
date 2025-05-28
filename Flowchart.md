flowchart TD
A[User] -->|Sign up / Login| B[API Platform]
B --> C[User DB - PostgreSQL]
A -->|Joins channel| B
B --> D[Channel Service]
D --> E[Channel DB - PostgreSQL]
A -->|Sends message| B
B --> F[Message Service]
F --> G[MongoDB - Messages]
F -->|Publish| H[Mercure Hub]
H --> I[Other Connected Users]
B -->|Check presence| J[Redis - User Status]
A -->|Presence Update| J

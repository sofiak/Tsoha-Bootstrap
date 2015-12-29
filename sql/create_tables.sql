-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE USERS(
   id             SERIAL PRIMARY KEY,
   username       VARCHAR(25)  NOT NULL,
   password       VARCHAR(25)  NOT NULL,
   created_at     TIMESTAMP NOT NULL,
   modified_at    TIMESTAMP NOT NULL
);

CREATE TABLE TASKS(
   id             SERIAL PRIMARY KEY,
   user_id        INTEGER REFERENCES USERS(id),
   title          VARCHAR(50)  NOT NULL,
   description    VARCHAR(200) NOT NULL,
   priority       INTEGER   NOT NULL,
   due_date       DATE      NOT NULL,
   status         INTEGER   NOT NULL,
   created_at     TIMESTAMP NOT NULL,
   modified_at    TIMESTAMP NOT NULL
);

CREATE TABLE CATEGORIES(
   id             SERIAL PRIMARY KEY,
   user_id        INTEGER REFERENCES USERS(id),
   title          VARCHAR(25)  NOT NULL,
   created_at     TIMESTAMP NOT NULL,
   modified_at    TIMESTAMP NOT NULL
);

CREATE TABLE TASK_CATEGORY(
   id             SERIAL PRIMARY KEY,
   task_id        INTEGER REFERENCES TASKS(id),
   category_id    INTEGER REFERENCES CATEGORIES(id),
   created_at     TIMESTAMP NOT NULL,
   modified_at    TIMESTAMP NOT NULL
);

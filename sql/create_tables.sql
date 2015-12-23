-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE TASK(
   ID INTEGER PRIMARY KEY DEFAULT NEXTVAL(‘SERIAL’),
   USER_ID        INT       NOT NULL,
   TITLE          TEXT(50)  NOT NULL,
   DESCRIPTION    TEXT(200) NOT NULL,
   PRIORITY       INT       NOT NULL,
   DUE_DATE       DATE      NOT NULL,
   STATUS         INT       NOT NULL,
   CREATED_AT     TIMESTAMP NOT NULL,
   MODIFIED_AT    TIMESTAMP NOT NULL,
);


CREATE TABLE USER(
   ID INTEGER PRIMARY KEY DEFAULT NEXTVAL(‘SERIAL’),
   USERNAME       TEXT(25)  NOT NULL,
   PASSWORD       TEXT(25)  NOT NULL,
   AGE            INT       NOT NULL,
   CREATED_AT     TIMESTAMP NOT NULL,
   MODIFIED_AT    TIMESTAMP NOT NULL,
);

CREATE TABLE CATEGORY(
   ID INTEGER PRIMARY KEY DEFAULT NEXTVAL(‘SERIAL’),
   USER_ID        INT       NOT NULL,
   TITLE          TEXT(25)  NOT NULL,
   CREATED_AT     TIMESTAMP NOT NULL,
   MODIFIED_AT    TIMESTAMP NOT NULL,
);

CREATE TABLE TASK_CATEGORY(
   ID INTEGER PRIMARY KEY DEFAULT NEXTVAL(‘SERIAL’),
   TASK_ID        INT       NOT NULL,
   CATEGORY_ID    INT       NOT NULL
);

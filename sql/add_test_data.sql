-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO USERS (username, password, created_at, modified_at) VALUES ('BATMAN', 'BANAANI', NOW(), NOW());

INSERT INTO TASKS (user_id, title, description, priority, due_date, status, created_at, modified_at) VALUES (1, 'PESE BATMOBIL', 'PESE JA VAHAA BATMOBIL ENNEN KUIN ALFRED HERMOSTUU', 1, '2016-01-10', 0, NOW(), NOW());

INSERT INTO CATEGORIES (user_id, title, created_at, modified_at) VALUES (1, 'VARUSTEHUOLTO', NOW(), NOW());

INSERT INTO TASK_CATEGORY(task_id, category_id, created_at, modified_at) VALUES (1, 1, NOW(), NOW());

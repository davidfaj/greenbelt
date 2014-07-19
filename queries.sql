SELECT * FROM users;

SELECT * FROM incidents ORDER BY created_at ASC;

SELECT incidents.id, incidents.name, incidents.created_at, users.first_name, 'yes', 'go'
FROM incidents
LEFT JOIN users ON incidents.creator_id = users.id
ORDER BY created_at DESC;

SELECT * FROM users_has_incidents;

SELECT users.first_name, users.last_name
FROM users_has_incidents
JOIN users ON users.id=users_has_incidents.users_id
JOIN incidents ON incidents.id=users_has_incidents.incidents_id;

INSERT INTO users_has_incidents (users_id, incidents_id) VALUES (1, 3);
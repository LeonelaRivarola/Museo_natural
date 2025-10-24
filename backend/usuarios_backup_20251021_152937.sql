-- Backup de la tabla usuarios
-- Fecha: 2025-10-21 15:29:37

UPDATE usuarios SET password = 'hash_admin123' WHERE id = 1;
UPDATE usuarios SET password = 'hash_editor456' WHERE id = 2;

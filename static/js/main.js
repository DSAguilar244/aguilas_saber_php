// Archivo de JavaScript para funciones interactivas

document.addEventListener('DOMContentLoaded', () => {
    // Agrega un evento de clic a cada tile
    const tiles = document.querySelectorAll('.tile');
    tiles.forEach(tile => {
        tile.addEventListener('click', () => {
            alert(`Has seleccionado: ${tile.querySelector('.tile-name').textContent}`);
        });
    });

    // Cambia el color del título al pasar el mouse
    const header = document.querySelector('.page-header h1');
    header.addEventListener('mouseover', () => {
        header.style.color = '#e74c3c';
    });
    header.addEventListener('mouseout', () => {
        header.style.color = '#2c3e50';
    });
});

const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');
const app = express();
const port = 3000;

// Configurar la base de datos
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: 'tu_contraseña',
  database: 'tu_base_de_datos'
});

// Conectar a la base de datos
db.connect(err => {
  if (err) throw err;
  console.log('Conectado a la base de datos');
});

// Middleware para parsear los datos del formulario
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

// Ruta para manejar el formulario de registro
app.post('/register', (req, res) => {
  const { userName, userLastName, userEmail, userPhone, userPassword, userAddress, userRole } = req.body;

  if (!userPassword || userPassword !== req.body.userConfirmPassword) {
    return res.status(400).send('Las contraseñas no coinciden');
  }

  const query = 'INSERT INTO usuarios (nombre, apellido, correo, telefono, contrasena, direccion, rol) VALUES (?, ?, ?, ?, ?, ?, ?)';
  db.query(query, [userName, userLastName, userEmail, userPhone, userPassword, userAddress, userRole], (err, result) => {
    if (err) throw err;

    res.redirect('/gestionar-usuarios');
  });
});

// Ruta para gestionar los usuarios (mostrar los usuarios registrados)
app.get('/gestionar-usuarios', (req, res) => {
  db.query('SELECT * FROM usuarios', (err, rows) => {
    if (err) throw err;
    res.send(`
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
          </tr>
        </thead>
        <tbody>
          ${rows.map(user => `
            <tr>
              <td>${user.id}</td>
              <td>${user.nombre} ${user.apellido}</td>
              <td>${user.correo}</td>
              <td>${user.rol}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    `);
  });
});

// Iniciar el servidor
app.listen(port, () => {
  console.log(`Servidor ejecutándose en http://localhost:${port}`);
});
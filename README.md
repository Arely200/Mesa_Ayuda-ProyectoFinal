<div align="center">

# Mesa de Ayuda - Proyecto Final

### Sistema Web para la Gestión de Incidencias y Soporte Técnico

Proyecto Final desarrollado para la **Universidad Tecnológica de Panamá**.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white)

</div>

---

# Tabla de Contenido

- [Descripción](#-descripción)
- [Objetivos](#-objetivos)
- [Características](#-características)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Capturas del Sistema](#-capturas-del-sistema)
- [Roles del Sistema](#-roles-del-sistema)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Instalación](#-instalación)
- [Base de Datos](#-base-de-datos)
- [Flujo del Sistema](#-flujo-del-sistema)
- [Módulos](#-módulos)
- [Repositorio](#-repositorio)
- [Recursos](#-recursos)
- [Autora](#-autora)
- [Licencia](#-licencia)

---

# Descripción

**Mesa de Ayuda** es una aplicación web desarrollada en **PHP** que permite administrar solicitudes de soporte técnico mediante un sistema de tickets.

El sistema facilita el registro, seguimiento y resolución de incidencias, permitiendo que administradores, agentes y colaboradores trabajen de manera organizada para brindar soporte eficiente.

---

# Objetivos

- Automatizar el proceso de atención de incidencias.
- Facilitar el seguimiento de cada ticket.
- Optimizar los tiempos de respuesta.
- Centralizar toda la información de soporte.
- Mejorar la organización del departamento de TI.

---

# Características

- Inicio de sesión seguro.
- Administración de usuarios.
- Gestión de colaboradores.
- Registro de tickets.
- Seguimiento de incidencias.
- Reportes administrativos.
- Página de noticias.
- Página de contacto.
- Consulta pública.
- Control de acceso por roles.

---

# Tecnologías Utilizadas

| Tecnología | Descripción |
|------------|-------------|
| PHP 8 | Backend |
| MySQL | Base de datos |
| HTML5 | Estructura |
| CSS3 | Diseño |
| JavaScript | Interactividad |
| Bootstrap 5 | Framework CSS |
| Apache | Servidor Web |
| WAMP | Entorno de desarrollo |
| Git | Control de versiones |
| GitHub | Repositorio |

---

# Capturas del Sistema

## Inicio de Sesión

![Login](https://placehold.co/1200x650?text=Pantalla+de+Inicio+de+Sesion)

---

## Dashboard Principal

![Dashboard](https://placehold.co/1200x650?text=Dashboard+Principal)

---

## Gestión de Tickets

![Tickets](https://placehold.co/1200x650?text=Gestion+de+Tickets)

---

## Gestión de Usuarios

![Usuarios](https://placehold.co/1200x650?text=Gestion+de+Usuarios)

---

## Reportes

![Reportes](https://placehold.co/1200x650?text=Modulo+de+Reportes)

---

# Roles del Sistema

## Administrador

- Gestionar usuarios.
- Gestionar colaboradores.
- Gestionar agentes.
- Administrar tickets.
- Generar reportes.
- Administrar el sistema.

---

## Agente

- Visualizar tickets asignados.
- Actualizar estados.
- Resolver incidencias.
- Consultar historial.

---

## Colaborador

- Crear tickets.
- Consultar estado.
- Dar seguimiento.

---

## Usuario Público

- Consultar información.
- Contactar soporte.
- Visualizar noticias.

---

# Estructura del Proyecto

```text
Mesa_Ayuda
│
├── database
│   └── migrations
│
├── public
│
├── src
│   ├── Config
│   ├── Controllers
│   ├── Core
│   ├── Helpers
│   ├── Models
│   ├── Views
│
├── assets
│
└── README.md
```

---

# Instalación

## 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/Arely200/MesaAyuda_ProyectoFinal.git
```

---

## 2️⃣ Entrar al proyecto

```bash
cd MesaAyuda_ProyectoFinal
```

---

## 3️⃣ Copiar el proyecto

Mover la carpeta al directorio:

```
C:\wamp64\www\
```

---

## 4️⃣ Crear la base de datos

```sql
CREATE DATABASE mesa_ayuda;
```

---

## 5️⃣ Importar la base de datos

Importar el archivo SQL ubicado en:

```
database/migrations/
```

---

## 6️⃣ Configurar la conexión

Modificar los parámetros de conexión de la base de datos en los archivos de configuración del proyecto.

---

## 7️⃣ Iniciar WAMP

- Apache
- MySQL

---

## 8️⃣ Ejecutar el sistema

Abrir el navegador:

```
http://localhost/Mesa_Ayuda
```

---

# Base de Datos

La base de datos incluye las tablas principales para:

- Usuarios
- Colaboradores
- Agentes
- Tickets
- Reportes
- Autenticación
- Historial
- Estados
- Categorías

---

# Flujo del Sistema

```text
Usuario
    │
    ▼
Crear Ticket
    │
    ▼
Administrador
    │
    ▼
Asignar Agente
    │
    ▼
Atención
    │
    ▼
Seguimiento
    │
    ▼
Resolución
    │
    ▼
Cierre del Ticket
```

---

# Módulos

Inicio de sesión

Dashboard

Gestión de Usuarios

Gestión de Colaboradores

Gestión de Agentes

Gestión de Tickets

Reportes

Noticias

Contacto

Consulta Pública

---

# Funcionalidades

- Registro de usuarios.
- Inicio de sesión.
- Administración de perfiles.
- Administración de tickets.
- Seguimiento de incidencias.
- Reportes administrativos.
- Panel de administración.
- Gestión de colaboradores.
- Gestión de agentes.
- Consulta pública.

---

# Requisitos

- PHP 8 o superior.
- MySQL 8.
- Apache.
- WAMP Server.
- Navegador moderno.
- Git (opcional).

---

# Repositorio

## GitHub

**Repositorio del Proyecto**

https://github.com/Arely200/MesaAyuda_ProyectoFinal

---

# Recursos

- PHP → https://www.php.net/

- Bootstrap → https://getbootstrap.com/

- MySQL → https://www.mysql.com/

- JavaScript → https://developer.mozilla.org/es/docs/Web/JavaScript

- HTML → https://developer.mozilla.org/es/docs/Web/HTML

- CSS → https://developer.mozilla.org/es/docs/Web/CSS

- Git → https://git-scm.com/

- GitHub → https://github.com/

---

# Autores

**Arely Mendoza**
**Estrella Pino**
**Nescker Santana**

Universidad Tecnológica de Panamá

Facultad de Ingeniería de Sistemas Computacionales

Proyecto Final

Año 2026

---

# Licencia

Este proyecto fue desarrollado con fines académicos para la **Universidad Tecnológica de Panamá**.

Se permite su consulta y uso como referencia educativa.

---

<div align="center">

### Si este proyecto te resulta útil, no olvides darle una estrella al repositorio.

**Gracias por visitar este proyecto.**

</div>
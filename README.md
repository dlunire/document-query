# API para Consultas al SAIME

## Introducción

Esta API fue desarrollada por **Código Entrópico** —anteriormente **Códigos del Futuro**—, un proyecto de **David E. Luna M.** orientado a la creación de herramientas que automaticen la integración de datos ciudadanos en sistemas digitales dentro del contexto venezolano.

El propósito principal de esta API es **proveer un punto de acceso unificado** que permita obtener información básica de identificación proveniente del SAIME (Servicio Administrativo de Identificación, Migración y Extranjería) de forma estructurada, precisa y legible por máquina.

A través de un **parser interno**, la API traduce el contenido HTML de la fuente oficial del SAIME a **formato JSON**, facilitando su consumo por aplicaciones externas.
Por ejemplo, una respuesta típica puede tener el siguiente formato:

```json
{
  "nationality": "V",
  "document": ---,
  "firstname": "---",
  "middlename": "---",
  "first_surname": "---",
  "second_surname": "---",
  "birthdate": "---",
  "gender": "---",
  "deceased": false
}
```

---

## Requisitos mínimos

Debe contar con:
- PHP 8.2+ en adelante para poder utilizarlo.

## Instalación

Antes de usar la API debe proceder a instalar la herramienta escribiendo el siguiente comando:

```bash
git clone git@github.com:dlunire/document-query.git
```

## Uso básico

Una vez haya instalado la herramienta en su servidor debe proceder a enviar la petición al servidor:

```bash
GET /api/v1/saime/:type/:document
```

Donde `:type` es el tipo de documento y `:document` es el número de documento. Los valores soportados son los siguientes:

- `V`: Venezolano.
- `E`: Extranjero.
- `DNI`: Otro documento.

El tipo de documento no distingue mayúsculas de minúsculas, por ejemplo, puede enviar la petición así:

```bash
GET /api/v1/saime/v/00000000
```

Donde `v` es el timpo de documento, que también puede ser `V` y `00000000` es el número de documento.



---

## Aplicaciones prácticas

El uso de esta API tiene un amplio rango de aplicaciones en sistemas donde la verificación o el registro de datos personales es necesaria, tales como:

* **Sistemas de registro médico:** validación automática de identidad para evitar duplicidad de historiales clínicos.
* **Plataformas bancarias y financieras:** verificación de identidad en procesos de apertura de cuentas o solicitudes de crédito.
* **Sistemas electorales o de participación ciudadana:** validación previa de los datos de los votantes o participantes.
* **Sistemas educativos:** automatización del registro de estudiantes en universidades o instituciones públicas.
* **Aplicaciones gubernamentales o municipales:** sincronización de bases de datos ciudadanas para trámites administrativos.
* **Servicios privados y fintech:** validación de identidad y mitigación de fraude en línea.

---

## **Beneficios técnicos y operativos**

* **Automatización completa del proceso de consulta.**
  Evita la necesidad de manipular o interpretar manualmente los datos provenientes del SAIME.

* **Estandarización de formato.**
  Todos los resultados se devuelven en **JSON**, simplificando la interoperabilidad con cualquier sistema backend o frontend.

* **Integración simple y versionada.**
  Mediante rutas como `GET /api/v1/saime/:type/:document`, las aplicaciones pueden consumir datos de forma estable y mantenible en el tiempo.

* **Reducción de errores humanos.**
  Al eliminar la digitación manual de datos, se reducen los errores comunes en registros masivos.

* **Diseñada exclusivamente para el contexto venezolano.**
  La API está optimizada para el formato y estructura de datos del SAIME, asegurando coherencia y precisión.

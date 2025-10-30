# API de Consultas al SAIME

## Introducción

Esta API, desarrollada por **Código Entrópico** (anteriormente **Códigos del Futuro**) y liderada por **David E. Luna M.**, permite acceder a información ciudadana de manera rápida, precisa y estructurada en el contexto venezolano.

A través de un **parser interno**, convierte automáticamente el HTML oficial del SAIME a **JSON legible por máquinas**, listo para integrarse en cualquier sistema digital.

Ejemplo de respuesta:

```json
{
  "nationality": "V",
  "document": 00000000,
  "firstname": "---",
  "middlename": "---",
  "first_surname": "---",
  "second_surname": "---",
  "birthdate": "01 de enero de 2002",
  "gender": "---",
  "deceased": false
}
```

---

## Requisitos

* PHP 8.2+
* Acceso a Internet desde el servidor para consultar los datos del SAIME

---

## Instalación

```bash
git clone git@github.com:dlunire/document-query.git
```

Puede correr la API en su computadora para probarla, por ejemplo:

```bash
cd document-query
php -S localhost:4000 -t public/
```

O directamente, instalarla en su servidor o hosting.

---

## Uso básico

**Ruta de la API:**

```http
GET /api/v1/saime/:type/:document
```

* `:type` → Tipo de documento: `V` (Venezolano), `E` (Extranjero), `DNI` (Otro)
* `:document` → Número de documento

Ejemplo de petición:

```http
GET /api/v1/saime/v/12345678
```

> La API no distingue mayúsculas de minúsculas en el tipo de documento.

---

## Aplicaciones prácticas

Esta API facilita la integración y validación de identidad en sistemas donde los datos ciudadanos son críticos:

* **Registro médico:** evita duplicidad de historiales clínicos.
* **Banca y fintech:** verificación rápida para apertura de cuentas y solicitudes de crédito.
* **Sistemas educativos:** registro automático de estudiantes.
* **Trámites gubernamentales y municipales:** sincronización confiable de bases de datos.
* **Sistemas electorales o de participación ciudadana:** validación previa de votantes o participantes.

> En general, cualquier sistema que requiera la verificación de identidad venezolana puede beneficiarse de esta API.

---

## Beneficios clave

1. **Automatización completa:** sin necesidad de manipular datos manualmente.
2. **Formato estándar JSON:** fácil integración con cualquier sistema backend o frontend.
3. **Integración estable y versionada:** rutas `/api/v1/...` para mantener compatibilidad futura.
4. **Reducción de errores humanos:** elimina la digitación manual de datos.
5. **Optimizada para Venezuela:** garantiza precisión y coherencia con el formato oficial del SAIME.

> Esta API está diseñada para el contexto venezolano, con posibilidad de expansión a otros países siempre que la legislación lo permita.
SIPAF
SISTEMA INTEGRAL DE PARQUEADEROS DE F.N.G           SECCIONAL QUINDIO


Propuesta de solución tecnológica “aplicación web” para la gestión integral y centralizada de los parqueaderos institucionales de la Fiscalía General de la Nación – Seccional Quindío.


Presentado por:

Nombre del aprendiz: VICTOR ALFONSO GUTIERREZ LOPEZ
Programa: Tecnología en Análisis y Desarrollo de Software – SENA
Cargo operativo: Guarda de seguridad asignado mediante contrato con la empresa de seguridad privada GRANADINA DE VIGILANCIA LTDA.


1. INTRODUCCIÓN

En el marco del proceso de formación como Tecnólogo en Análisis y Desarrollo de Software del Servicio Nacional de Aprendizaje – SENA, se presenta el desarrollo del proyecto “SIPAF”, una solución tecnológica diseñada para optimizar el control y la trazabilidad de los vehículos que ingresan y salen de los diferentes parqueaderos institucionales de la Fiscalía General de la Nación, Seccional Quindío, vehículos tanto oficiales como aquellos vehículos incautados y siniestros viales, los cuales ingresan y salen de las instalaciones con un control actual de minutas escritas físicas.

Esta herramienta busca responder a una necesidad identificada en campo, desde el ejercicio directo de funciones como guarda de seguridad en los diferentes puntos de control vehicular, donde actualmente persiste el uso de registros físicos (libros o minutas), los cuales dificultan el control, la consulta y la validación eficiente de la información.

2. OBJETIVO GENERAL

Desarrollar una aplicación web centralizada para la gestión digital y segura de los ingresos y salidas de vehículos en los parqueaderos institucionales de la Fiscalía General de la Nación – Seccional Quindío, reemplazando los registros físicos actuales por un sistema robusto, confiable y accesible desde cualquier dispositivo autorizado.


3. PARQUEADEROS INCLUIDOS EN LA PROPUESTA

El sistema integrará los siguientes parqueaderos, con funcionalidades adaptadas a cada uno:

- Parqueadero Único Galilea (Km 5, vía Armenia – Montenegro): Vehículos incautados, siniestros y particulares. Control administrativo y empresa contratista de seguridad sobre entradas, salidas, observaciones y almacenamiento de evidencias (fotos, documentos).

- Palacio de Justicia, CAF, CTI Armenia, Parqueadero  La Giralda: Vehículos oficiales operativos. Registro de placas, fechas, horas, nombre del funcionario asignado y sede.

- CACYM: Visualización en tiempo real de vehículos oficiales dentro o fuera de las instalaciones. Control cruzado con la base de datos para validación remota.


4. ALCANCE FUNCIONAL

- Registro de ingreso y salida de vehículos incautados, siniestros y oficiales.
- Captura de fotografías y observaciones al momento del registro.
- Control de funcionarios que operan los vehículos oficiales.
- Asignación de permisos por roles (guarda, administrador, supervisor, auditor).
- Reportes en tiempo real y exportables (PDF / Excel).
- Acceso multiplataforma desde navegador web (Chrome, Firefox, Edge, breve. Etc.) 
- Base de datos centralizada, con conexión segura y respaldo constante.

5. JUSTIFICACIÓN

El proyecto responde a una necesidad real y urgente de modernización tecnológica dentro de las sedes operativas de la Fiscalía en el Quindío. Actualmente, el uso de libros físicos como medio de control vehicular genera riesgos en la seguridad, demoras en la trazabilidad y limitaciones en la validación de movimientos históricos.

La implementación de esta herramienta permitiría:

- Mayor transparencia y eficiencia en el control vehicular institucional.
- Disponibilidad de información en tiempo real desde múltiples sedes.
- Trazabilidad de la operación logística de vehículos oficiales.
- Reducción de errores manuales y tiempos de respuesta.
- Avance significativo hacia la transformación digital en los procesos administrativos de seguridad.

6. TECNOLOGÍA PROPUESTA

- Lenguaje de desarrollo: PHP (versión 8 o superior)
- Base de datos: MySQL (phpMyAdmin o Workbench)
- Interfaz web: HTML5, CSS3, JavaScript responsivo para acceso móvil
- Sistema de roles: Accesos diferenciados según perfil (guarda, administrador, auditor, etc.)
- Respaldo y seguridad: Backups automáticos, HTTPS, control de sesión, cifrado de contraseñas

7. IMPACTO ESPERADO

- Eliminación total de libros físicos en el control de parqueaderos.
- Información vehicular centralizada y auditable.
- Control simultáneo desde múltiples sedes.
- Aumento de la confianza institucional y trazabilidad interna.
- Posibilidad de escalar el sistema a otras seccionales o regiones.

8. CONSIDERACIONES FINALES

Este proyecto se presenta desde una perspectiva de aporte tecnológico institucional, sin fines comerciales. Su diseño y desarrollo se fundamentan en la observación directa de los procesos actuales y las limitaciones operativas que enfrentan los funcionarios encargados del control vehicular, proponiendo una solución completamente adaptable al contexto operativo de la Seccional Quindío.


REQUERIMIENTOS FUNCIONALES Y NO FUNCIONALES 
CÓDIGO	REQUERIMIENTO FUNCIONAL
RF01	El sistema deberá contar con una página de inicio de sesión (login) que permita autenticación mediante usuario (ID), contraseña y rol.
RF02	El sistema deberá validar credenciales y permitir acceso únicamente si el usuario está registrado y autorizado en la base de datos.
RF03	El sistema deberá solicitar el cambio de contraseña periódico según políticas de seguridad definidas por el administrador.
RF04	El sistema deberá permitir a los usuarios registrar ingreso y salida de vehículos oficiales, incautados y particulares, almacenando: placa, tipo de vehículo, nombre del conductor o funcionario, hora, fecha y observaciones.
RF05	El sistema deberá permitir cargar o tomar fotografías en tiempo real del vehículo, vinculándolas al registro correspondiente.
RF06	El sistema deberá organizar la información por módulos de parqueadero (sedes), donde cada sede podrá operar de forma independiente, pero almacenará datos en una base de datos centralizada.
RF07	El sistema deberá incluir una tabla de control de entradas y salidas por sede, visible y gestionada por usuarios con rol de administración.
RF08	El sistema deberá permitir la visualización en tiempo real de vehículos dentro y fuera de cada sede.
RF09	El sistema deberá exportar reportes en formato Excel o PDF sobre: ingresos, salidas, estadísticas por sede, funcionario y tipo de vehículo.
RF10	El sistema deberá tener un panel de administración para la gestión de usuarios, contraseñas, roles y sedes.
RF11	El sistema deberá ser accesible desde cualquier dispositivo (computador, tablet, celular) mediante navegador web (interfaz responsive).
RF12	El sistema deberá permitir el registro completo del vehículo y conductor, incluyendo datos de contacto cuando se requiera.
RF13	El sistema deberá permitir agregar observaciones detalladas por parte del guarda o responsable al momento del ingreso/salida.
RF14	El portal web contara con un reloy y fecha de zona en todo tiempo visible.
RF15	El portal web contara con la información sobre la versión actual del sistema se inicio con la V 1.1

CÓDIGO
	REQUERIMIENTO NO FUNCIONAL
RNF01	El sistema deberá estar desarrollado en PHP y utilizar MySQL/MariaDB como motor de base de datos, gestionada mediante phpMyAdmin.
RNF02	El sitio web deberá ser responsivo, es decir, adaptable a distintos tamaños de pantalla y dispositivos.
RNF03	El sistema deberá implementar mecanismos de seguridad robustos: cifrado de contraseñas, validación de sesiones activas, e inactividad por tiempo prolongado.
RNF04	El sistema deberá permitir ser escalado fácilmente, tanto en número de sedes como en funcionalidades futuras.
RNF05	La estructura del sistema deberá ser modular, para facilitar el mantenimiento y la expansión por módulos funcionales.
RNF06	La interfaz del sistema deberá ser intuitiva, accesible y orientada al usuario final operativo (guardas, administrativos, supervisores).
RNF06	La base de datos deberá contar con un sistema de respaldo periódico automático para evitar pérdida de información.
RNF08	El sistema deberá registrar logs de actividad de los usuarios, para efectos de auditoría y trazabilidad.
RNF09	El sistema deberá estar protegido contra accesos no autorizados mediante verificación de credenciales por rol y sede.
RNF10	El sistema deberá estar preparado para operar bajo una conexión a internet estándar con uso eficiente de recursos y carga rápida.


MODELO ENTIDAD RELACION 




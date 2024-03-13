# generador-factuers
Sencillo generador de facturas en PDF

Tecnologías utilizadas:
PHP con DOMPF para la generación de los PDFS.
CSS con Bootstrap para dar estilos a la app.
Pequeña funcionalidad con JQuery.

Funcionalidades:
- Página principal en la que el usuario introduce todos los datos necesarios para la creación de la factura.
- Una vez enviados los datos se abre automáticamente el PDF generado para que el usuario lo revise y lo guarde.
- Además el programa guarda automáticamente todos los PDFs generados en la carpeta 'Facturas' con el nombre del cliente y la fecha de creación.
- El IVA es variable, lo establece el usuario en la creación de cada factura (por defecto 21%). En función de esto, el programa calcula el importe del iva y lo suma a la base para obtener el total.
- Se pueden añadir tantos conceptos como se deseen.
- La imagen de fondo del PDF se podrá editar sustituyendo la imagen llamada background.png.

Este programa a sido creado específicamente para las necesidades de una empresa concreta, pero se puede realizar modificaciones a partir de este.

# PROYECTO FINAL CRYPTO WALLET
## Integrantes
Jesús Lezaun<br>
Mikel Ariceta<br>
Raúl Sánchez<br>

## Forma de trabajo y organización
Primero hicimos el set up todos juntos en clase dirigido por Jesús. En este set up Jesús se encargó de poner a punto el precommit y las actions de GitHub. Planteamos el proyecto de forma conjunta. Posteriormente Mikel definió los issues del GET y Jesús los issues del POST.

Una vez definidos los issues Mikel escribió una parte de código de los GET, planteó sus dudas en clase con Jesús y Raúl y entre todos realizamos algunas decisiones del diseño. Una vez completados los GET, realizamos las pull request y el análisis de las mismas y su posterior integración en la rama master.
Seguidamente Raúl se puso a programar el caso de crear una cartera y Jesús el de comprar una moneda. Las dudas se solucionaron conjuntamente en clase y una vez acabado el caso de comprar una moneda y su correspondiente pull request, Raúl programó el caso de vender una moneda basándose en este. Para terminar, Mikel realizó la integración con la API y Jesús la integración con la caché.

Todos los issues fueron realizados en distintas ramas y validados con su correspondiente pull request. Además, en la realización de los issues nos dimos cuenta de algún fallo de diseño en casos previamente mergeados a master, por lo que realizamos los cambios pertinentes en sus correspondientes ramas y posteriormente lo integramos en la rama master.

Tras completar el proyecto hicimos una sesión de testing manual con Postman para comprobar que todos los casos funcionaban correctamente.

## Cosas a tener en cuenta
En la realización del proyecto no se ha llevado un TDD estricto pero la lógica de negocio está cubierta con sus respectivos tests unitarios y de integración. 

En el caso de la integración con la API podríamos haber llevado a cabo una mayor separación de responsabilidades pero debido a la simplicidad de los casos y siguiendo el principio KISS hemos optado por simplificar la implementación realizandola con una única clase con sus correspondientes tests.

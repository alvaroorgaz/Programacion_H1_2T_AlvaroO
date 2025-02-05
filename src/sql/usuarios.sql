select usuarios.id,
usuarios.nombre,
usuarios.apellidos,
usuarios.correo,
usuarios.edad,
usuarios.plan_base,
paquetes.nombre,
usuarios.duracion_suscripcion,
paquetes.precio,
planes.precio,
paquetes.precio + planes.precio coste_mensual
from usuarios
left join suscripciones
on usuarios.id = suscripciones.usuario_id
left join paquetes
on suscripciones.paquete_id = paquetes.id
left join planes on usuarios.plan_base = planes.nombre;
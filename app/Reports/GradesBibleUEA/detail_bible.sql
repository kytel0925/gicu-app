SELECT
    seleccion_academica.id AS seleccion_academica_id,
    nodo_academico.id AS nodo_academico_id,
    persona.id AS persona_id,
    inscripcion.id AS inscripcion_id,
    persona.numero_carnet,
    persona.nombre,
    persona.apellido,
    persona.dni,
    persona.pasaporte,
    persona.sexo as Sexo,
    persona.fecha_nacimiento as FechaNacimiento,
    (SELECT group_concat(nombre_pais.es_ES)
     FROM persona_nacionalidad
              INNER JOIN pais AS pn ON persona_nacionalidad.pais_id=pn.id
              INNER JOIN i18n_texto AS nombre_pais ON pn.nombre = nombre_pais.id
     WHERE persona_nacionalidad.persona_id = persona.id) AS nacionalidad,
    (SELECT group_concat(pn.secuencia1)
     FROM persona_nacionalidad
              INNER JOIN pais AS pn ON persona_nacionalidad.pais_id=pn.id
     WHERE persona_nacionalidad.persona_id = persona.id) AS cod_nacionalidad,
    (SELECT group_concat(nombre_pais.es_ES)
     FROM direccion
              INNER JOIN pais ON direccion.pais_id = pais.id
              INNER JOIN i18n_texto AS nombre_pais ON pais.nombre = nombre_pais.id
     WHERE direccion.class='Persona'
       AND direccion.class_id = persona.id
       AND direccion.principal = 1
       AND direccion.nacceso = 'publico') AS pais_residencia,
    (SELECT group_concat(pais.secuencia1)
     FROM direccion
              INNER JOIN pais ON direccion.pais_id = pais.id
     WHERE direccion.class='Persona'
       AND direccion.class_id = persona.id
       AND direccion.principal = 1
       AND direccion.nacceso = 'publico') AS cod_pais_residencia,
    (SELECT group_concat(poblacion.nombre)
     FROM direccion
              INNER JOIN poblacion ON direccion.poblacion_id = poblacion.id
     WHERE direccion.class='Persona'
       AND direccion.class_id = persona.id
       AND direccion.principal = 1
       AND direccion.nacceso = 'publico') AS poblacion_residencia,
    (SELECT group_concat(concat_ws('',poblacion.cpro,poblacion.cmun))
     FROM direccion
              INNER JOIN poblacion ON direccion.poblacion_id = poblacion.id
     WHERE direccion.class='Persona'
       AND direccion.class_id = persona.id
       AND direccion.principal = 1
       AND direccion.nacceso = 'publico') AS cod_poblacion_residencia,
    nombre_estado_inscrip.es_ES AS estado_inscripcion,
    periodo_lectivo.nombre AS periodo_lectivo_matricula,
    PLI.nombre AS periodo_lectivo_inscripcion,
    (SELECT group_concat(distinct username)
     FROM credencial
              INNER JOIN credencial_servicio_web ON credencial.id = credencial_servicio_web.credencial_id
     WHERE credencial.expediente_id = expediente.id
       AND servicio_web_id = 3
     GROUP BY expediente.id) AS login,
    programa.abreviatura AS programa,
    IF(programa_version.id IN ('7344', '7345', '7346', '7367', '7390', '7391', '7392', '7414'), 'Extracurricular','Oficial') as Fase,
    convocatoria.nombre AS convocatoria,
    nodo_academico.abreviatura as abreviatura_asignatura,
    nombre_nodo.es_ES AS NombreAsignatura_nodo,
    replace (record_academico.nota, '.',',') as nota,
    replace (nodo_academico.creditos,'.',',') as creditos,
    matricula.id AS matricula_id,
    admision.id AS admision_id,
    IFNULL((SELECT '1'
            FROM record_academico
                     INNER JOIN reconocimiento rec on rec.record_academico_id = record_academico.id
            WHERE record_academico.seleccion_academica_id = seleccion_academica.id
              AND record_academico.class_id = nodo_academico.id
              AND record_academico.class ='nodoacademico'
              AND rec.tipo_reconocimiento_id in (1,6)
              AND rec.valida = 1
              AND record_academico.nacceso ='publico'),
           '0') AS reconocimiento,
    IFNULL((SELECT '1'
            FROM record_academico
                     INNER JOIN convalidacion conv on conv.record_academico_id = record_academico.id
            WHERE record_academico.seleccion_academica_id = seleccion_academica.id
              AND record_academico.class_id = nodo_academico.id
              AND record_academico.class ='nodoacademico'
              AND conv.valida = 1
              AND record_academico.nacceso ='publico'),
           '0') AS convalidacion,
    IF( record_academico.nota >= 5,'1','0') AS aprobado,
    IF(record_academico.tipo_nota <> '_NO_PRESENTADA',IF(record_academico.nota < 5 AND record_academico.nota IS NOT NULL, '1','0'),'0') AS suspensa,
    IF(record_academico.tipo_nota = '_NO_PRESENTADA',1,0) AS no_presentado,
    seleccion_academica.veces,
    seleccion_academica.erasmus
FROM inscripcion
         INNER JOIN persona ON persona.id = persona_id
         INNER JOIN expediente on expediente.persona_id = persona.id
         LEFT JOIN matricula ON inscripcion.id = matricula.inscripcion_id
         LEFT JOIN programa_version ON matricula.programa_version_id = programa_version.id
         INNER JOIN programa_version AS programa_version_inscripcion ON inscripcion.programa_version_id = programa_version_inscripcion.id
         INNER JOIN programa ON programa_version_inscripcion.programa_id = programa.id
         LEFT JOIN admision ON admision.matricula_id = matricula.id
         LEFT JOIN seleccion_academica ON admision.id = admision_id
    AND seleccion_academica.nacceso='publico'
         LEFT JOIN plaza_academica ON plaza_academica.id = plaza_academica_id
    AND plaza_academica.nacceso ='publico'
         LEFT JOIN nodo_academico ON nodo_academico.id = plaza_academica.class_id
    AND plaza_academica.class='nodoacademico'
         LEFT JOIN record_academico ON record_academico.seleccion_academica_id = seleccion_academica.id
    AND record_academico.class_id = nodo_academico.id
    AND record_academico.class ='nodoacademico'
         LEFT JOIN asignatura_version ON asignatura_version.id = nodo_academico.class_id
    AND nodo_academico.class = 'AsignaturaVersion'
         LEFT JOIN asignatura ON asignatura.id = asignatura_version.asignatura_id
         LEFT JOIN convocatoria ON admision.convocatoria_id = convocatoria.id
         LEFT JOIN actividad ON nodo_academico.class='Actividad'
    AND nodo_academico.class_id = actividad.id
         LEFT JOIN i18n_texto AS nombre_nodo ON nombre_nodo.id = nodo_academico.nombre

         LEFT JOIN periodo_lectivo ON periodo_lectivo.id = matricula.periodo_lectivo_id
         LEFT JOIN periodo_lectivo PLI ON PLI.id = inscripcion.periodo_lectivo_id
         LEFT JOIN estado AS estado_inscripcion ON inscripcion.estado_id = estado_inscripcion.id
         LEFT JOIN i18n_texto AS nombre_estado_inscrip ON estado_inscripcion.nombre = nombre_estado_inscrip.id
WHERE inscripcion.nacceso = 'publico'
  AND persona.nacceso = 'publico'
  AND matricula.nacceso='publico'
  AND admision.nacceso='publico'
  AND programa.tipo_programa_id = 9
    /*where[AND periodo_lectivo.id in(:academic_period)]*/
ORDER BY periodo_lectivo.id ASC,
    inscripcion.id ASC,
    convocatoria.id ASC

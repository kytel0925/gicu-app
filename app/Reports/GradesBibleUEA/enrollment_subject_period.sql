select
    asignatura.id as subject_id,
    asignatura.abreviatura as subject_abbr,
    asignatura_version.version as subject_version,
    sa.id as academic_selection_id,
    sa.veces as academic_selection_times
from matricula inner join admision a on matricula.id = a.matricula_id
inner join seleccion_academica sa on a.id = sa.admision_id
inner join plaza_academica pa on sa.plaza_academica_id = pa.id
inner join nodo_academico on pa.class = 'NodoAcademico' and nodo_academico.id = pa.id
inner join asignatura_version on nodo_academico.class = 'AsignaturaVersion' AND asignatura_version.id = nodo_academico.class_id
inner join asignatura on asignatura.id = asignatura_version.asignatura_id
where
    matricula.inscripcion_id = ?/*:inscription*/
AND matricula.periodo_lectivo_id = ?/*:academic_period*/
AND asignatura.abreviatura = ?/*:abbr*/
AND matricula.nacceso = 'publico'
AND a.nacceso = 'publico'
AND sa.nacceso = 'publico'
ORDER BY a.convocatoria_id

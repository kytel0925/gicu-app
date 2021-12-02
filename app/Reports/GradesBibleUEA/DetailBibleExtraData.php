<?php

namespace App\Reports\GradesBibleUEA;

use CTDesarrollo\Regulus\Models\ReportRequest;
use CTDesarrollo\Regulus\Traits\WithRawQueriesSupport;
use CTDesarrollo\Regulus\Traits\WithRuntimeConnections;
use Exception;
use Illuminate\Database\Query\Builder;

/**
 * Class DetailBibleQuery
 * @package App\Reports\GradesBibleUEA
 */
class DetailBibleExtraData
{
    use WithRuntimeConnections, WithRawQueriesSupport;

    const DOCUMENT_TYPE_NIF = 'NIF';
    const DOCUMENT_TYPE_NIE = 'NIE';
    const DOCUMENT_TYPE_PASSPORT = 'PASAPORTE';
    const DOCUMENT_TYPE_OTHERS = 'OTROS';

    const FIELD_EXTRA_KEY = 'data_adicional';

    /**
     * @var ReportRequest
     */
    protected $request;

    /**
     * @var mixed
     */
    protected $bible;

    /**
     * @param mixed $bibleRecord
     * @param ReportRequest $request
     */
    public function __construct($bibleRecord, ReportRequest $request)
    {
        $this->request = $request;
        //clone the record
        $this->bible = (object) (array) $bibleRecord;
    }

    public function request()
    {
        return $this->request;
    }

    /**
     * @return Builder
     */
    public function get()
    {
        $this->setExtraKey();

        $this->setTimesTakenRecord();

        return $this->bible;
    }

    /**
     * @return static
     */
    protected function setExtraKey()
    {
        $extraKey = $this->getBibleValue('inscripcion_id');
        $extraKey .= $this->getBibleValue( 'periodo_lectivo_matricula');
        $extraKey .= $this->getBibleValue( 'abreviatura_asignatura');

        $this->setBibleValue('data_adicional', $extraKey);

        return $this;
    }

    protected function setTimesTakenRecord()
    {
        //messy thing with the key, it's better to do it with a new sub query
        $times = $this->getBibleValue( 'veces');
        $announcement = $this->getBibleValue('convocatoria');

        if($times == 0 && strtolower($announcement) == 'extraordinaria'){
            //reading the original code the key is using to locate a previous fetched record with the time data if it exists it will use it if not it uses the original value
            //in here we use the runtime connection to get from the db in hot the time value

            try{
                $query = __DIR__ . '/enrollment_subject_period.sql';

                $builder = $this->builderFromRawSqlFile($query, $this->connection('guiaa')->db(), [
                    'inscription' => $this->getBibleValue('inscripcion_id'),
                    'academic_period' => $this->getBibleValue('periodo_lectivo_matricula'),
                    'abbr' => $this->getBibleValue('abreviatura_asignatura'),
                ]);

                $record = $builder->orderBy('academic_selection_times', 'desc')
                    ->first();

                $times = data_get($record, 'academic_selection_times', $times);

                $this->setBibleValue('veces', $times);
            }
            catch (Exception $ex){
                $this->setBibleValue('exceptions.setTimesTakenRecord', $ex->getMessage());
            }
        }

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getBibleValue($key, $default = null)
    {
        return data_get($this->bible, $key, $default);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return static
     */
    protected function setBibleValue($key, $value)
    {
        data_set($this->bible, $key, $value);

        return $this;
    }
}

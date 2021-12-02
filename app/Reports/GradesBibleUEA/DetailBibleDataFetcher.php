<?php

namespace App\Reports\GradesBibleUEA;

use CTDesarrollo\Regulus\ReportRequestDataFetcher;
use CTDesarrollo\Regulus\Traits\WithFetchDataTricks;
use CTDesarrollo\Regulus\Traits\WithRawQueriesSupport;
use CTDesarrollo\Regulus\Traits\WithRuntimeConnections;
use Exception;

/**
 * Class DataFetcher
 * @package App\Reports\GradesBibleUEA
 */
class DetailBibleDataFetcher extends ReportRequestDataFetcher
{
    use WithRuntimeConnections, WithFetchDataTricks, WithRawQueriesSupport;

    /**
     * @inheritDoc
     * @return $this
     * @throws Exception
     */
    public function fetchData(): DetailBibleDataFetcher
    {
        $query = __DIR__ . '/detail_bible.sql';
        $builder = $this->builderFromRawSqlFile($query, $this->connection('guiaa')->db());

        $startedAt = now();

        $this->fireStartEvent($startedAt, $builder->count());

        $builder->orderBy('periodo_lectivo_matricula')
            ->orderBy('inscripcion_id')
            ->orderBy('convocatoria');

        $this->chuckBuilder($builder, 1000, function($row, $index, $page){
            $decorator = new DetailBibleExtraData($row, $this->request());

            return $this->storePayload($decorator->get());
        });

        $this->fireFinishEvent(now());

        return $this;
    }
}

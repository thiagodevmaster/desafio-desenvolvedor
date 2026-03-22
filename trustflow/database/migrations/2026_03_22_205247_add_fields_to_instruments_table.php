<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->string('Asst')->after('TckrSymb');
            $table->string('AsstDesc')->after('Asst');
            $table->string('SgmtNm')->after('AsstDesc');

            $table->date('XprtnDt')->nullable()->after('SctyCtgyNm');
            $table->string('XprtnCd')->nullable()->after('XprtnDt');
            $table->date('TradgStartDt')->after('XprtnCd');
            $table->date('TradgEndDt')->after('TradgStartDt');

            $table->integer('BaseCd')->nullable()->after('TradgEndDt');
            $table->string('ConvsCritNm')->nullable()->after('BaseCd');
            $table->integer('MtrtyDtTrgtPt')->nullable()->after('ConvsCritNm');
            $table->boolean('ReqrdConvsInd')->default(false)->after('MtrtyDtTrgtPt');
            $table->string('CFICd')->after('ISIN');
            
            $table->date('DlvryNtceStartDt')->nullable()->after('CFICd');
            $table->date('DlvryNtceEndDt')->nullable()->after('DlvryNtceStartDt');
            $table->string('OptnTp')->nullable()->after('DlvryNtceEndDt');
            $table->decimal('CtrctMltplr', 18, 8)->nullable()->after('OptnTp');
            $table->integer('AsstQtnQty')->nullable()->after('CtrctMltplr');
            $table->integer('AllcnRndLot')->after('AsstQtnQty');
            $table->string('TradgCcy', 5)->after('AllcnRndLot');
            $table->string('DlvryTpNm')->nullable()->after('TradgCcy');

            $table->integer('WdrwlDays')->nullable()->after('DlvryTpNm');
            $table->integer('WrkgDays')->nullable()->after('WdrwlDays');
            $table->integer('ClnrDays')->nullable()->after('WrkgDays');
            $table->string('RlvrBasePricNm')->nullable()->after('ClnrDays');
            $table->integer('OpngFutrPosDay')->nullable()->after('RlvrBasePricNm');

            $table->bigInteger('SdTpCd1')->nullable()->after('OpngFutrPosDay');
            $table->string('UndrlygTckrSymb1')->nullable()->after('SdTpCd1');
            $table->bigInteger('SdTpCd2')->nullable()->after('UndrlygTckrSymb1');
            $table->string('UndrlygTckrSymb2')->nullable()->after('SdTpCd2');

            $table->string('PureGoldWght')->nullable()->after('UndrlygTckrSymb2');
            $table->decimal('ExrcPric', 18, 8)->nullable()->after('PureGoldWght');
            $table->string('OptnStyle')->nullable()->after('ExrcPric');
            $table->string('ValTpNm')->nullable()->after('OptnStyle');
            $table->boolean('PrmUpfrntInd')->default(false)->after('ValTpNm');
            $table->date('OpngPosLmtDt')->nullable()->after('PrmUpfrntInd');

            $table->integer('DstrbtnId')->after('OpngPosLmtDt');
            $table->integer('PricFctr')->after('DstrbtnId');
            $table->integer('DaysToSttlm')->after('PricFctr');
            $table->string('SrsTpNm')->nullable()->after('DaysToSttlm');
            $table->boolean('PrtcnFlg')->default(false)->after('SrsTpNm');
            $table->boolean('AutomtcExrcInd')->default(false)->after('PrtcnFlg');
            $table->string('SpcfctnCd')->after('AutomtcExrcInd');
            $table->date('CorpActnStartDt')->after('CrpnNm');
            $table->string('CtdyTrtmntTpNm')->after('CorpActnStartDt');
            $table->bigInteger('MktCptlstn')->after('CtdyTrtmntTpNm');
            $table->string('CorpGovnLvlNm')->nullable()->after('MktCptlstn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn([
                'Asst', 'AsstDesc', 'SgmtNm', 'XprtnDt', 'XprtnCd', 'TradgStartDt',
                'TradgEndDt', 'BaseCd', 'ConvsCritNm', 'MtrtyDtTrgtPt', 'ReqrdConvsInd',
                'CFICd', 'DlvryNtceStartDt', 'DlvryNtceEndDt', 'OptnTp', 'CtrctMltplr',
                'AsstQtnQty', 'AllcnRndLot', 'TradgCcy', 'DlvryTpNm', 'WdrwlDays',
                'WrkgDays', 'ClnrDays', 'RlvrBasePricNm', 'OpngFutrPosDay', 'SdTpCd1',
                'UndrlygTckrSymb1', 'SdTpCd2', 'UndrlygTckrSymb2', 'PureGoldWght',
                'ExrcPric', 'OptnStyle', 'ValTpNm', 'PrmUpfrntInd', 'OpngPosLmtDt',
                'DstrbtnId', 'PricFctr', 'DaysToSttlm', 'SrsTpNm', 'PrtcnFlg',
                'AutomtcExrcInd', 'SpcfctnCd', 'CorpActnStartDt', 'CtdyTrtmntTpNm',
                'MktCptlstn', 'CorpGovnLvlNm'
            ]);
        });
    }
};
<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Tests\Entity;

use AppBundle\DBAL\Types\Baseline\GUFFastsaettesEfterType;
use AppBundle\Entity\Baseline;
use AppBundle\Entity\BaselineKorrektion;
use AppBundle\Entity\ELOKategori;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversNothing
 */
class BaselineTest extends KernelTestCase
{
    public function testCalculateVarmeStrafafkoelingsafgiftKorrigeret()
    {
        $baseline = new Baseline();

        $baseline->setVarmeStrafafkoelingsafgift(100.0);
        $baseline->setVarmeStrafafkoelingsafgiftKorrektion(-10.0);

        $baseline->calculate();
        $this->assertSame(90.0, $baseline->getVarmeStrafafkoelingsafgiftKorrigeret());
    }

    public function testCalculateElBaselineFastsatForEjendomKorrigeret()
    {
        $baseline = new Baseline();

        $k1 = new BaselineKorrektion();
        $k1->setKorrektionEl(-20.0);
        $k1->setIndvirkning(true);
        $baseline->addKorrektioner($k1);

        $k2 = new BaselineKorrektion();
        $k2->setKorrektionEl(10.0);
        $k2->setIndvirkning(true);
        $baseline->addKorrektioner($k2);

        $k3 = new BaselineKorrektion();
        $k3->setKorrektionEl(10.0);
        $k3->setIndvirkning(false);
        $baseline->addKorrektioner($k3);

        $baseline->calculate();
        $this->assertSame(-10.0, $baseline->getElBaselineFastsatForEjendomKorrektion());
        $this->assertSame(-10.0, $baseline->getElBaselineFastsatForEjendomKorrigeret());

        $baseline->setElBaselineFastsatForEjendom(100.0);
        $baseline->calculate();
        $this->assertSame(90.0, $baseline->getElBaselineFastsatForEjendomKorrigeret());
    }

    public function testCalculateVarmeGAFForbrugKorrigeret()
    {
        $baseline = new Baseline();

        $k1 = new BaselineKorrektion();
        $k1->setKorrektionGAF(-30.0);
        $k1->setIndvirkning(true);
        $baseline->addKorrektioner($k1);

        $k2 = new BaselineKorrektion();
        $k2->setKorrektionGAF(10.0);
        $k2->setIndvirkning(true);
        $baseline->addKorrektioner($k2);

        $k3 = new BaselineKorrektion();
        $k3->setKorrektionGAF(10.0);
        $k3->setIndvirkning(false);
        $baseline->addKorrektioner($k3);

        $baseline->calculate();
        $this->assertSame(-20.0, $baseline->getVarmeGAFForbrugKorrektion());
        $this->assertSame(-20.0, $baseline->getVarmeGAFForbrugKorrigeret());

        $baseline->setVarmeGAFForbrug(100.0);
        $baseline->calculate();
        $this->assertSame(80.0, $baseline->getVarmeGAFForbrugKorrigeret());
    }

    public function testCalculateVarmeGUFForbrugKorrigeret()
    {
        $baseline = new Baseline();

        $k1 = new BaselineKorrektion();
        $k1->setKorrektionGUF(-40.0);
        $k1->setIndvirkning(true);
        $baseline->addKorrektioner($k1);

        $k2 = new BaselineKorrektion();
        $k2->setKorrektionGUF(10.0);
        $k2->setIndvirkning(true);
        $baseline->addKorrektioner($k2);

        $k3 = new BaselineKorrektion();
        $k3->setKorrektionGUF(10.0);
        $k3->setIndvirkning(false);
        $baseline->addKorrektioner($k3);

        $baseline->calculate();
        $this->assertSame(-30.0, $baseline->getVarmeGUFForbrugKorrektion());
        $this->assertSame(-30.0, $baseline->getVarmeGUFForbrugKorrigeret());

        $baseline->setVarmeGUFForbrug(100.0);
        $baseline->calculate();
        $this->assertSame(70.0, $baseline->getVarmeGUFForbrugKorrigeret());
    }

    public function testCalculateElPrimaer()
    {
        $baseline = new Baseline();
        $baseline->setElForbrugsdataPrimaer1Forbrug(32.0);
        $baseline->setElForbrugsdataPrimaer3Forbrug(64.0);
        $baseline->calculate();

        $this->assertSame(48.0, $baseline->getElForbrugsdataPrimaerGennemsnit());
        $this->assertNull($baseline->getElForbrugsdataPrimaerNoegetal());

        $baseline->setArealTilNoegletalsanalyse(96.0);
        $baseline->calculate();

        $this->assertSame(0.5, $baseline->getElForbrugsdataPrimaerNoegetal());
    }

    public function testCalculateElSekundaer()
    {
        $baseline = new Baseline();
        $baseline->setElForbrugsdataSekundaer1Forbrug(32.0);
        $baseline->setElForbrugsdataSekundaer3Forbrug(64.0);
        $baseline->calculate();

        $this->assertSame(48.0, $baseline->getElForbrugsdataSekundaerGennemsnit());
        $this->assertNull($baseline->getElForbrugsdataSekundaerNoegetal());

        $baseline->setArealTilNoegletalsanalyse(96.0);
        $baseline->calculate();

        $this->assertSame(0.5, $baseline->getElForbrugsdataSekundaerNoegetal());
    }

    public function testCalculateElBaselineNoegletalForEjendom()
    {
        $baseline = new Baseline();
        $baseline->setArealTilNoegletalsanalyse(160.0);
        $baseline->calculate();

        $this->assertNull($baseline->getElBaselineNoegletalForEjendom());

        $baseline->setElBaselineFastsatForEjendom(80.0);
        $baseline->calculate();

        $this->assertSame(0.5, $baseline->getElBaselineNoegletalForEjendom());
    }

    public function testCalculateGUFRegAar()
    {
        $eloKategori = new ELOKategori();
        $eloKategori->setAndelVarmeGUFFaktor(.2);

        $baseline = new Baseline();
        $baseline->setEloKategori($eloKategori);
        $baseline->calculate();

        $this->assertNull($baseline->getVarmeForbrugsdataPrimaer1GUFRegAar());
        $this->assertInstanceOf(ELOKategori::class, $baseline->getEloKategori());

        $baseline->setVarmeForbrugsdataPrimaer1Forbrug(50.0);
        $baseline->setVarmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
        $baseline->calculate();

        $this->assertSame(10.0, $baseline->getVarmeForbrugsdataPrimaer1GUFRegAar());

        $baseline->setVarmeForbrugsdataPrimaer1SamletVarmeforbrugJuniJuliAugust(12.0);
        $baseline->calculate();

        $this->assertSame(10.0, $baseline->getVarmeForbrugsdataPrimaer1GUFRegAar());

        $baseline->setVarmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::SAMLET_MAANEDSFORBRUG_FOR_JUNI_JULI_AUGUST);
        $baseline->calculate();

        $this->assertSame(48.0, $baseline->getVarmeForbrugsdataPrimaer1GUFRegAar());
    }

    public function testCalculateGAFRegAar()
    {
        $eloKategori = new ELOKategori();
        $eloKategori->setAndelVarmeGUFFaktor(.2);

        $baseline = new Baseline();
        $baseline->setEloKategori($eloKategori);
        $baseline->calculate();

        $this->assertNull(null, $baseline->getVarmeForbrugsdataPrimaer1GAFRegAar());

        $baseline->setVarmeForbrugsdataPrimaer1Forbrug(50.0);
        $baseline->setVarmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
        $baseline->calculate();

        $this->assertSame(40.0, $baseline->getVarmeForbrugsdataPrimaer1GAFRegAar());
    }

    public function testCalculateVarmeForbrugsdata()
    {
        $eloKategori = new ELOKategori();
        $eloKategori->setAndelVarmeGUFFaktor(.2);

        $baseline = new Baseline();
        $baseline->setEloKategori($eloKategori);
        $baseline->calculate();

        $baseline->setArealTilNoegletalsanalyse(95.0);

        $baseline->setVarmeForbrugsdataPrimaer1Forbrug(50.0);
        $baseline->setVarmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
        $baseline->setVarmeForbrugsdataPrimaer1GDPeriode(1500.0);

        $baseline->setVarmeForbrugsdataPrimaer2Forbrug(100.0);
        $baseline->setVarmeForbrugsdataPrimaer2GDPeriode(3000.0);

        $baseline->calculate(3000.0);

        $this->assertSame(80.0, $baseline->getVarmeForbrugsdataPrimaer1GAFnormal());
        $this->assertSame(90.0, $baseline->getVarmeForbrugsdataPrimaer1ForbrugKlimakorrigeret());

        $this->assertSame(80.0, $baseline->getVarmeForbrugsdataPrimaer2GAFnormal());
        $this->assertSame(100.0, $baseline->getVarmeForbrugsdataPrimaer2ForbrugKlimakorrigeret());

        // Assert averages
        $this->assertSame(80.0, $baseline->getVarmeForbrugsdataPrimaerGAFGennemsnit());
        $this->assertSame(15.0, $baseline->getVarmeForbrugsdataPrimaerGUFGennemsnit());
        $this->assertSame(95.0, $baseline->getVarmeForbrugsdataPrimaerGennemsnitKlimakorrigeret());

        $this->assertSame(1.0, $baseline->getVarmeForbrugsdataPrimaerNoegletal());
    }

    public function testCalculateSekundaerGUFRegAar()
    {
        $eloKategori = new ELOKategori();
        $eloKategori->setAndelVarmeGUFFaktor(.2);

        $baseline = new Baseline();
        $baseline->setEloKategori($eloKategori);
        $baseline->calculate();

        $this->assertNull($baseline->getVarmeForbrugsdataSekundaer1GUFRegAar());
        $this->assertInstanceOf(ELOKategori::class, $baseline->getEloKategori());

        $baseline->setVarmeForbrugsdataSekundaer1Forbrug(50.0);
        $baseline->setVarmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
        $baseline->calculate();

        $this->assertSame(10.0, $baseline->getVarmeForbrugsdataSekundaer1GUFRegAar());

        $baseline->setVarmeForbrugsdataSekundaer1SamletVarmeforbrugJuniJuliAugust(12.0);
        $baseline->calculate();

        $this->assertSame(10.0, $baseline->getVarmeForbrugsdataSekundaer1GUFRegAar());

        $baseline->setVarmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::SAMLET_MAANEDSFORBRUG_FOR_JUNI_JULI_AUGUST);
        $baseline->calculate();

        $this->assertSame(48.0, $baseline->getVarmeForbrugsdataSekundaer1GUFRegAar());
    }

    public function testCalculateSekundaerGAFRegAar()
    {
        $eloKategori = new ELOKategori();
        $eloKategori->setAndelVarmeGUFFaktor(.2);

        $baseline = new Baseline();
        $baseline->setEloKategori($eloKategori);
        $baseline->calculate();

        $this->assertNull(null, $baseline->getVarmeForbrugsdataSekundaer1GAFRegAar());

        $baseline->setVarmeForbrugsdataSekundaer1Forbrug(50.0);
        $baseline->setVarmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
        $baseline->calculate();

        $this->assertSame(40.0, $baseline->getVarmeForbrugsdataSekundaer1GAFRegAar());
    }

    public function testCalculateSekundaerVarmeForbrugsdata()
    {
        $eloKategori = new ELOKategori();
        $eloKategori->setAndelVarmeGUFFaktor(.2);

        $baseline = new Baseline();
        $baseline->setEloKategori($eloKategori);
        $baseline->calculate();

        $baseline->setArealTilNoegletalsanalyse(95.0);

        $baseline->setVarmeForbrugsdataSekundaer1Forbrug(50.0);
        $baseline->setVarmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
        $baseline->setVarmeForbrugsdataSekundaer1GDPeriode(1500.0);

        $baseline->setVarmeForbrugsdataSekundaer2Forbrug(100.0);
        $baseline->setVarmeForbrugsdataSekundaer2GDPeriode(3000.0);

        $baseline->calculate(3000.0);

        $this->assertSame(80.0, $baseline->getVarmeForbrugsdataSekundaer1GAFnormal());
        $this->assertSame(90.0, $baseline->getVarmeForbrugsdataSekundaer1ForbrugKlimakorrigeret());

        $this->assertSame(80.0, $baseline->getVarmeForbrugsdataSekundaer2GAFnormal());
        $this->assertSame(100.0, $baseline->getVarmeForbrugsdataSekundaer2ForbrugKlimakorrigeret());

        // Assert averages
        $this->assertSame(80.0, $baseline->getVarmeForbrugsdataSekundaerGAFGennemsnit());
        $this->assertSame(15.0, $baseline->getVarmeForbrugsdataSekundaerGUFGennemsnit());
        $this->assertSame(95.0, $baseline->getVarmeForbrugsdataSekundaerGennemsnitKlimakorrigeret());

        $this->assertSame(1.0, $baseline->getVarmeForbrugsdataSekundaerNoegletal());
    }

    public function testCalculateVarmeBaselineFastsatForEjendomAndCalculateVarmeBaselineNoegletalForEjendom()
    {
        $baseline = new Baseline();
        $baseline->setArealTilNoegletalsanalyse(200.0);
        $baseline->setVarmeGAFForbrug(50.0);
        $baseline->setVarmeGUFForbrug(50.0);
        $baseline->calculate();

        $this->assertSame(100.0, $baseline->getVarmeBaselineFastsatForEjendom());
        $this->assertSame(0.5, $baseline->getVarmeBaselineNoegletalForEjendom());
    }
}

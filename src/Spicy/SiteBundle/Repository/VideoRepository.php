<?php

namespace Spicy\SiteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Spicy\RankingBundle\Entity\Ranking;
use Doctrine\ORM\Query;
use Doctrine\ORM\NoResultException;
use Spicy\SiteBundle\Entity\Artiste;

/**
 * VideoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VideoRepository extends EntityRepository
{
    private $retro = 2; //2 en prod, 6 en dev

    public function getOneAvecArtistes($id)
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.id=:id')
            ->setParameter('id', $id)
            ->andWhere('v.etat=1');

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getJson($id)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.genre_musicaux', 'g')
            ->where('v.id=:id')
            ->setParameter('id', $id)
            ->andWhere('v.etat=1')
            ->andWhere('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->addSelect('a')
            ->addSelect('g');

        return $qb->getQuery()->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }

    public function getAvecArtistes($nbOccurrences, $top = false)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')
            ->setFirstResult(0)
            ->setMaxResults($nbOccurrences)
            ->addSelect('a');

        if ($top) {
            $qb->addOrderBy('v.onTop', 'DESC');
        }

        $qb->addOrderBy('v.dateVideo', 'DESC');

        $query = $qb->getQuery();

        return new Paginator($query);
    }

    public function getSuiteAvecArtistes($page, $premierResultat, $nbOccurrences, $videoIdsList)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('Page inexistante (page = ' . $page . ')');
        }

        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')
            ->andWhere('v.id NOT IN (:list)')
            ->setParameter('list', $videoIdsList)
            ->setFirstResult(($page - 1) * $nbOccurrences)
            ->setMaxResults($nbOccurrences)
            ->addSelect('a')
            ->orderBy('v.dateVideo', 'DESC');
        $query = $qb->getQuery();

        return new Paginator($query);
        //return $query->getResult();
    }

    public function getSuite($page, $nbOccurrences, $videoIdsList = [])
    {
        $query = $this->getSuiteRequest($page, $nbOccurrences, $videoIdsList);

        return new Paginator($query, true);
    }

    public function getSuiteJson($page, $nbOccurrences, $videoIdsList = [])
    {
        /*$query = $this->getIds($page, $nbOccurrences, $videoIdsList);
        
        return $query->getResult();*/
        $query = $this->getSuiteRequest($page, $nbOccurrences, $videoIdsList)->getQuery();

        $query->setHydrationMode(Query::HYDRATE_ARRAY);

        return new Paginator($query, true);
    }

    public function getSuiteRequest($page, $nbOccurrences, $videoIdsList)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('Page inexistante (page = ' . $page . ')');
        }

        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')
            ->setFirstResult(($page - 1) * $nbOccurrences)
            ->setMaxResults($nbOccurrences)
            ->addSelect('a')
            ->orderBy('v.dateVideo', 'DESC');

        if (count($videoIdsList)) {
            $qb->andWhere('v.id NOT IN (:list)')
                ->setParameter('list', $videoIdsList);
        }

        /*$query = $qb->getQuery();

        return $query;*/

        return $qb;
    }

    public function getIds($page, $nbOccurrences, $videoIdsList = [])
    {
        if ($page < 1) {
            throw $this->createNotFoundException('Page inexistante (page = ' . $page . ')');
        }

        $qb = $this->createQueryBuilder('v')
            //->join('v.artistes', 'a')
            ->andWhere('v.etat=1')
            ->setFirstResult(($page - 1) * $nbOccurrences)
            ->setMaxResults($nbOccurrences)
            ->orderBy('v.dateVideo', 'DESC');

        $query = $qb->getQuery();

        return $query;
    }

    public function getSuiteAjax($videoIdsList)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')
            ->andWhere('v.id NOT IN (:list)')
            ->setParameter('list', $videoIdsList)
            ->orderBy('v.dateVideo', 'DESC');
        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getByArtiste($id, $nbOccurrences = null, $first = 0)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->where('a.id=:id')
            ->setParameter('id', $id)
            ->andWhere('v.etat=1')
            ->addOrderBy('v.dateVideo', 'DESC')
            ->addSelect('a');

        if ($nbOccurrences) {
            $qb->setFirstResult($first)
                ->setMaxResults($nbOccurrences);
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function getByGenre($id, $nbOccurrences, $page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('Page inexistante (page = ' . $page . ')');
        }

        $qb = $this->createQueryBuilder('v')
            ->join('v.genre_musicaux', 'g')
            ->join('v.artistes', 'a')
            ->where('g.id=:id')
            ->setParameter('id', $id)
            ->andWhere('v.etat=1')
            ->addOrderBy('v.dateVideo', 'DESC')
            ->setFirstResult(($page - 1) * $nbOccurrences)
            ->setMaxResults($nbOccurrences)
            ->addSelect('g')
            ->addSelect('a');

        $query = $qb->getQuery();
        return new Paginator($query);
    }

    public function getAllRetro($nbOccurrences)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.type_videos', 't')
            ->join('v.genre_musicaux', 'g')
            ->join('v.artistes', 'a')
            ->where("g.id=" . $this->retro)
            ->andWhere('v.etat=1')
            ->setFirstResult(0)
            ->setMaxResults($nbOccurrences)
            ->addOrderBy('v.dateVideo', 'DESC')
            ->addSelect('a')
            ->addSelect('t')
            ->addSelect('g');

        return $qb->getQuery()->getResult();
    }

    public function getRetro()
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.type_videos', 't')
            ->join('v.genre_musicaux', 'g')
            ->join('v.artistes', 'a')
            ->where("g.slug= 'retro'")
            ->andWhere('v.etat=1')
            ->andWhere("v.dateVideo in (select max(vi.dateVideo) from SpicySiteBundle:video vi 
                        JOIN vi.genre_musicaux ge 
                        where ge.slug= 'retro')")
            ->addOrderBy('v.dateVideo', 'DESC')
            ->addSelect('a')
            ->addSelect('t')
            ->addSelect('g');

        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            $result = null;
        }

        return $result;
    }

    public function getAll($page, $nbOccurences)
    {
        $qb = $this->createQueryBuilder('v')
            ->setFirstResult(($page - 1) * $nbOccurences)
            ->setMaxResults($nbOccurences)
            ->orderBy('v.titre')
            ->addOrderBy('v.dateVideo');

        $query = $qb->getQuery();
        return new Paginator($query);
    }

    public function getSuggestions($idList, $id)
    {
        $sql = $this->createQueryBuilder('v')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id IN (:list)')
            ->andWhere('v.etat=1')
            ->andWhere('v.id <> :id')
            ->orderBy('v.dateVideo', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(4);

        $sql->setParameter('list', $idList);
        $sql->setParameter('id', $id);
        $query = $sql->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function getSuggestionsArtistes($idList)
    {
        if (empty($idList))
            $idList[] = 0;

        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id in (' . implode(',', $idList) . ')')
            ->andWhere('v.etat = 1')
            ->setFirstResult(0)
            ->setMaxResults(20)
            ->addSelect('a');

        return $qb->getQuery()->getResult();
    }

    public function getByTag($id, $page, $nbOccurrences)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.genre_musicaux', 'g')
            ->join('v.artistes', 'a')
            ->join('v.hashtags', 'h')
            ->where('h.id=:id')
            ->setParameter('id', $id)
            ->andWhere('v.etat=1')
            ->addOrderBy('v.dateVideo', 'DESC')
            ->setFirstResult(($page - 1) * $nbOccurrences)
            ->setMaxResults($nbOccurrences)
            ->addSelect('g')
            ->addSelect('a');

        $query = $qb->getQuery();
        return new Paginator($query);
    }

    public function getTops($page, $nbOccurences)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.type_videos', 't')
            ->setFirstResult(($page - 1) * $nbOccurences)
            ->setMaxResults($nbOccurences)
            ->where("t.libelle = 'Top'")
            ->orderBy('v.dateVideo', 'DESC')
            ->addOrderBy('v.dateVideo')
            ->addSelect('a');

        $query = $qb->getQuery();
        return new Paginator($query);
    }

    public function getTopByDate(Ranking $ranking, $max = 10)
    {
        $qb =  $this->createQueryBuilder('v')
            ->join('v.videoRankings', 'vr')
            ->where('vr.ranking=:ranking')
            ->join('v.genre_musicaux', 'g', 'WITH', 'g.id<> :id_retro')
            ->join('v.type_videos', 't', 'WITH', 't.id= :id_type')
            ->setParameter('ranking', $ranking->getId())
            ->setParameter('id_retro', 2)
            ->setParameter('id_type', 1)
            ->orderBy('vr.nbVu', 'DESC')
            ->addOrderBy('v.dateVideo', 'DESC')
            ->setMaxResults($max)
            ->addSelect('vr')
            ->addSelect('t');

        //return $qb->getQuery()->getResult();
        $query = $qb->getQuery();
        return new Paginator($query);
    }

    public function getAvecArtistesFlux($nbOccurrences, $top = false)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')
            ->setFirstResult(0)
            ->setMaxResults($nbOccurrences)
            ->addSelect('a');

        if ($top) {
            $qb->leftJoin('v.videoRankings', 'vr')
                ->andWhere('v.onTop=1')
                ->orderBy('vr.nbVu');
        }

        $qb->addOrderBy('v.dateVideo', 'DESC');

        $query = $qb->getQuery();

        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getFlux($nbOccurrences, $top = false)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')
            ->setFirstResult(0)
            ->setMaxResults($nbOccurrences)
            ->orderBy('v.dateVideo', 'DESC');

        if ($top) {
            $qb->leftJoin('v.videoRankings', 'vr')
                ->andWhere('v.onTop=1');
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getOneForUpdate($id)
    {
        $qb = $this->createQueryBuilder('v')
            ->leftJoin('v.genre_musicaux', 'g')
            ->leftJoin('v.hashtags', 'h')
            ->leftJoin('v.artistes', 'a')
            ->leftJoin('v.collaborateurs', 'c')
            ->leftJoin('v.type_videos', 'tv')
            ->addSelect('g', 'h', 'a', 'c', 'tv')
            ->where('v.id=:id')
            ->setParameter('id', $id);

        $query = $qb->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

        return $query->getOneOrNullResult();
    }

    /**
     * 
     * @param type $nb
     * @param Artiste $artiste
     * @return type
     */
    public function getLastByArtiste($nb, Artiste $artiste)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.genre_musicaux', 'g')
            ->join('v.artistes', 'a')
            ->where('g.id <> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat = 1')
            ->andWhere('a.id = :artiste')
            ->setParameter('artiste', $artiste)
            ->setFirstResult(0)
            ->setMaxResults($nb)
            ->orderBy('v.dateVideo', 'DESC');

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
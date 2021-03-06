<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Entrenador;
use AppBundle\Entity\Equipo;
use AppBundle\Entity\Jugador;
use AppBundle\Entity\Partido;
use AppBundle\Entity\Usuario;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DatosIniciales implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        // Equipos
        $denominacionEquipos = [
            "Tintas Cómicas Industriales",
            "Los Bestiajos",
            "Frikies TV",
            "Japan Otaku"
        ];

        $equipos = [];

        foreach($denominacionEquipos as $denominacion) {
            $equipo = new Equipo();
            $equipo->setDenominacion($denominacion);
            $manager->persist($equipo);

            $equipos[] = $equipo;
        }

        // Jugadores
        $jugadores = [
            [0, "Juan", "Nadie Nadie", 25],
            [0, "Francisco", "Ibáñez Talavera", 75],
            [0, "Saturnino", "Bacterio", 55],
            [0, "Vicente", "Ruínez", 57],
            [0, "Filemón", "Pi", 42],
            [0, "Pantuflo", "Zapatilla Llobregat", 40],
            [0, "Telesforo", "Bestiájez", 35],
            [1, "Chuck", "Norris", 9999],
            [1, "Jean-Claude", "Van Damme", 60],
            [1, "Arnold", "Schwarzenegger", 65],
            [1, "Sylvester", "Stallone", 66],
            [1, "Bruce", "Willis", 58],
            [1, "Jackie", "Chan", 45],
            [1, "Wesley", "Snipes", 52],
            [2, "Carmen", "de Mairena", 90],
            [2, "Kiko", "Rivera Pantoja", 3],
            [2, "Boris Rodolfo", "Izaguirre Lobo", 42],
            [2, "Dinio", "García Leyva", 45],
            [2, "Kiko", "Matamoros Hernández", 58],
            [2, "Jorge Javier", "Vázquez", 46],
            [2, "Jimmy", "Giménez-Arnau Puente", 52],
            [3, "Goku", "Kakarotto", 33],
            [3, "Vegetta", "Vegetta", 35],
            [3, "Edward", "Elric", 20],
            [3, "Alfonse", "Elric", 18],
            [3, "Naruto", "Uzumaki", 21],
            [3, "Sasuke", "Uchiha", 22],
            [3, "Ash", "Ketchum", 15]
        ];

        foreach($jugadores as $persona) {
            $jugador = new Jugador();
            $jugador->setNombre($persona[1])
                ->setApellidos($persona[2])
                ->setEquipo($equipos[$persona[0]])
                ->setEdad($persona[3]);

            $manager->persist($jugador);
        }

        // Entrenadores
        $entrenadores = [
            ["Rompetechos", "No Veo", 45],
            ["Clint", "Eastwood", 30],
            ["Belén", "Esteban Menéndez", 1],
            ["Kame", "Sennin", 10]
        ];

        foreach($entrenadores as $i => $persona) {
            $entrenador = new Entrenador();
            $entrenador->setNombre($persona[0])
                ->setApellidos($persona[1])
                ->setEquipo($equipos[$i])
                ->setTemporadas($persona[2]);
            $entrenador->getEquipo()->setEntrenador($entrenador);

            $manager->persist($entrenador);
        }

        // Partidos
        $partidos = [
            ['2016-01-01', 0, 1, 2, 6],
            ['2016-01-02', 2, 3, 0, 4],
            ['2016-01-08', 3, 0, 1, 1],
            ['2016-01-09', 1, 2, 9, 0],
            ['2016-01-08', 0, 2, 3, 0],
            ['2016-01-09', 1, 3, 2, 1]
        ];

        foreach($partidos as $combinacion) {
            $partido = new Partido();
            $partido->setEquipoLocal($equipos[$combinacion[1]])
                ->setEquipoVisitante($equipos[$combinacion[2]])
                ->setMarcadorLocal($combinacion[3])
                ->setMarcadorVisitante($combinacion[4])
                ->setFechaCelebracion(new \DateTime($combinacion[0]));

            $manager->persist($partido);
        }

        // Usuario administrador
        $usuario = new Usuario();

        if ($usuario instanceof UserInterface) {
            $usuario
                ->setCorreoElectronico('admin@example.com')
                ->setClave($this->container->get('security.password_encoder')->encodePassword($usuario, 'admin'))
                ->setAdministrador(true)
                ->setArbitro(false)
                ->setComentarista(false);

            $manager->persist($usuario);
        }

            // Usuario arbitro
        $usuario = new Usuario();

        if ($usuario instanceof UserInterface) {
            $usuario
                ->setCorreoElectronico('arbitro@example.com')
                ->setClave($this->container->get('security.password_encoder')->encodePassword($usuario, 'penalti'))
                ->setAdministrador(false)
                ->setArbitro(true)
                ->setComentarista(false);

            $manager->persist($usuario);
        }

        // Usuario periodista
        $usuario = new Usuario();

        if ($usuario instanceof UserInterface) {
            $usuario
                ->setCorreoElectronico('periodista@example.com')
                ->setClave($this->container->get('security.password_encoder')->encodePassword($usuario, 'locutor'))
                ->setAdministrador(false)
                ->setArbitro(false)
                ->setComentarista(true);

            $manager->persist($usuario);
        }

        $manager->flush();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}

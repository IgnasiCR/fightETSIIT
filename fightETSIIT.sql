CREATE TABLE Jugadores (
    idUsuario int PRIMARY KEY,
    nombre varchar(50) NOT NULL UNIQUE,
    raza varchar(20) CHECK(raza in('informatico','teleco','intruso')),
    dinero int DEFAULT '0' NOT NULL,
    muertes int DEFAULT '0' NOT NULL,
    ataque int DEFAULT '5' NOT NULL,
    defensa int DEFAULT '5' NOT NULL,
    vida int DEFAULT '5' NOT NULL,
    nivel number(6) DEFAULT '1' NOT NULL,
    experiencia number(6) DEFAULT '0' NOT NULL,
    estado number(4) DEFAULT '0' NOT NULL,
    estado_pelea number(4) DEFAULT '0' NOT NULL,
    peleas_posibles int DEFAULT '30' NOT NULL,
    premium number(1) DEFAULT '0' NOT NULL
);

CREATE TABLE Luchas (
  idLucha int NOT NULL,
  jugadorUno int NOT NULL REFERENCES Jugadores(idUsuario),
  jugadorDos int NOT NULL REFERENCES Jugadores(idUsuario),
  fecha date NOT NULL,
  victoria int NOT NULL,
  tipo varchar(20)CHECK(tipo in('competitivo', 'amistoso')),
  PRIMARY KEY(idLucha, jugadorUno),
);

CREATE TABLE Objetos (
  idObjeto int PRIMARY KEY,
  nombre varchar(25) NOT NULL,
  raza varchar(20) CHECK(raza in('informatico','teleco','intruso')),
  dinero int NOT NULL,
  descripcion long NOT NULL,
  ataque int NOT NULL,
  defensa int NOT NULL,
  vida int NOT NULL
);

CREATE TABLE Compras (
  idCompra int NOT NULL,
  idUsuario int NOT NULL REFERENCES Jugadores(idUsuario),
  idObjeto int NOT NULL REFERENCES Objetos(idObjeto),
  fecha date NOT NULL,
  PRIMARY KEY(idCompra, fecha, idUsuario)
);

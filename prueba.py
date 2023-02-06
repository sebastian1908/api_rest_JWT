class Calculos:
    def __init__(self, respuesta):
        self.respuesta = respuesta

    def __hacerCalculos(self):
        try:
            while True:
                par = 0
                impar = 0
                lista = []
                imparLista = []
                sumadorPar = 0
                sumadorImPar = 0
                cantidadNumero = int(input('Digite la cantidad de numeros \n'))
                for i in range(cantidadNumero):
                    numero = int(input('Digite un numero \n'))
                    if numero % 2 == 0:
                        lista.append(numero)
                        par += 1
                        sumadorPar += numero
                    else:
                        imparLista.append(numero)
                        impar += 1
                        sumadorImPar += numero

                print("-----------------PARES--------------------")
                print(lista)
                print(f'La cantidad de numero pares es : {par}')
                print(f'La sumatoria de los numeros pares es : {sumadorPar}')
                print("-----------------IMPARES--------------------")
                print(imparLista)
                print(f'La cantidad de numero impares es {impar}')
                print(f'La sumatoria de los numeros impares es : {sumadorImPar}')
                break
        except:
            print('Error, no puedes ingresar valores de tipo string')    
    
    def __preguntarDeNuevo(self):
        while True:
            respuesta = input("¿Desea continuar con el calculo?: ")
            if(respuesta == 'si' or respuesta == 'Si' or respuesta == 'SI'):
                    Calculos.__hacerCalculos(self)
            else:
                print('Gracias hasta luego')
                break
    
    def operaciones(self):
        return self.__hacerCalculos()
       
    def preguntarValores(self):
      return self.__preguntarDeNuevo()

    def Errores():
        print("HHHHHHHHHH") 

data = Calculos('')
data.operaciones()
data.preguntarValores()



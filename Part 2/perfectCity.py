#Challenge 3: Zombies

#below is the function that takes zombies percentage in each city
#as a percentage paremeter and total number of city as a totalCity

def findCommonDivisor(cityA,cityB):
    #a function to determine if cityA and cityB has common divisor more or less than 1

    commonDivisor = 0
    while (cityB != 0):
            commonDivisor = cityB
            cityB = cityA % cityB
            cityA = commonDivisor
    return commonDivisor

def hasMoreZombies(nA, nB):
    #function to compare total zombies percentage in two cities
    if nA > nB:
        return True
    else:
        return False
    
def perfectCity(cities, totalCity):
    for i in range(totalCity-1): #because index starts from 0
        cityA = cities[i] 
        cityB = cities[i+1]
 
        if findCommonDivisor(cityA, cityB) == 1 and hasMoreZombies(cities[i], cities[i+1]): #checking condition for perfect city
            return ('The perfect city is at position {} of array which has total {}% of zombies.'.format(i, cities[i]))

    return "NO PERFECT CITY FOUND"

cities = [1,1,3,6,7,3]
totalCity = 6
print(perfectCity(cities, totalCity))

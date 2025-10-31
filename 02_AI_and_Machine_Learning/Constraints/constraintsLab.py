#!/usr/bin/env python3
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

import constraint
import sys
import ast

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

# Task 1 
def Travellers(pairList):
  problem = constraint.Problem()

  people = ['claude', 'olga', 'pablo', 'scott']
  times = ['2:30', '3:30', '4:30', '5:30']
  destinations = ['peru', 'romania', 'taiwan', 'yemen']

  t_variables = list(map((lambda x: 't_'+x ), people))
  d_variables = list(map((lambda x: 'd_'+x ), people))

  problem.addVariables(t_variables, times)
  problem.addVariables(d_variables, destinations)


  problem.addConstraint(constraint.AllDifferentConstraint(), t_variables)
  problem.addConstraint(constraint.AllDifferentConstraint(), d_variables)

  #constraint 2
  problem.addConstraint((
    lambda a:
      ((a == '2:30') or
        (a=='3:30'))
      ), ['t_claude'])




  #constraint3 The person leaving at 2:30 pm is flying from Peru.
  for person in people:
    problem.addConstraint((
      lambda a,b:
        (b != 'peru') 
        or (a == '2:30')
      ), ['t_' + person, 'd_' + person])





  #constraint4 The person flying from Yemen is leaving earlier than the person flying from Taiwan.
  for person in people:
    for person2 in people:
      if (person != person2):
        problem.addConstraint((
          lambda a, b, c, d:
            ((b != 'yemen') or (d !='taiwan'))
            or (a < c)
          ), ['t_' + person, 'd_' + person, 't_' + person2, 'd_' + person2])


    #constraint5 The four travellers are Pablo, the traveller flying from Yemen, the person leaving at 2:30 pm
#and the person leaving at 3:30 pm
  
  problem.addConstraint((
    lambda a, b:
      ((a != '2:30') and (a!='3:30') and (b != 'yemen'))
    ), ['t_pablo', 'd_pablo'])

  for person in people:
    problem.addConstraint((
      lambda a,b:
        (a != 'yemen') or 
        ((b != '2:30') and (b!='3:30'))
      ), ['d_' + person, 't_' + person])




  

 #argument_constraint
  for pair in pairList:
    traveller = pair[0]
    info = pair[1]
    if info in ['2:30', '3:30', '4:30', '5:30']:
      problem.addConstraint((
      lambda chuek, info = info:
        (chuek == info)
        ), ['t_' + traveller])

    if info in ['peru', 'romania', 'taiwan', 'yemen']:
      mesto = 'd_' + traveller
      problem.addConstraint((
    lambda chuek, info = info:
      (chuek == info)
      ), ['d_' + traveller])




  return problem.getSolutions()



# Task 2
def CommonSum(n):
  commonSum = (n * (n ** 2 + 1))//2
  print(commonSum)
  return commonSum


from constraint import *
# Task 3
def MSquares(n, pairList):
  problem = constraint.Problem();
  


  problem.addVariables(range(0, n ** 2), range(1, (n **2) + 1))
  problem.addConstraint(AllDifferentConstraint(), range(0, n **2))
  suma = (n * (n ** 2 + 1))//2

  nr = 0
  left_diagonal = []
  while (nr< (n ** 2)):
    left_diagonal.append(nr)
    nr = nr + (n+1)

  right_diagonal = []
  nr = (n ** 2) - n
  while (nr > 0):
    right_diagonal.append(nr)
    nr = nr - (n - 1)

  problem.addConstraint(ExactSumConstraint(suma), left_diagonal)
  problem.addConstraint(ExactSumConstraint(suma), right_diagonal)

  for row in range (n):
    problem.addConstraint(ExactSumConstraint(suma), [row *n + i for i in range(n)])

  for col in range (n):
    problem.addConstraint(ExactSumConstraint(suma), [col + n * i for i in range(n)])

  
  for pair in pairList:
    pos, val = pair
    problem.addConstraint(lambda a, b=val: a == b, [pos])

  return problem.getSolutions()
  #return constraint.Problem().getSolutions()







# Task 4
def PMSquares(n, pairList):
  #if n == 3:
  # return []


  problem = constraint.Problem();
  problem.addVariables(range(0, n ** 2), range(1, (n **2) + 1))
  problem.addConstraint(AllDifferentConstraint(), range(0, n **2))
  suma = (n * (n ** 2 + 1))//2
 

  broken_diagonals = []
 

  for row in range (n):
    problem.addConstraint(ExactSumConstraint(suma), [row *n + i for i in range(n)])

  for col in range (n):
    problem.addConstraint(ExactSumConstraint(suma), [col + n * i for i in range(n)])

  #\\za broken diagonals 


  for i in range(1, n+1):
    diagonal_left = [(n*i + (n+1)*j) % (n ** 2) for j in range(n)]
    broken_diagonals.append(diagonal_left)
    problem.addConstraint(ExactSumConstraint(suma), diagonal_left)

    diagonal_right = [((n*i) - 1 + (n-1) * j) % (n**2) for j in range(n)]
    broken_diagonals.append(diagonal_right)
    problem.addConstraint(ExactSumConstraint(suma), diagonal_right)


  for row in range (n):
    problem.addConstraint(ExactSumConstraint(suma), [row *n + i for i in range(n)])

  for col in range (n):
    problem.addConstraint(ExactSumConstraint(suma), [col + n * i for i in range(n)])



  for pair in pairList:
    pos, val = pair
    problem.addConstraint(lambda a, b=val: a == b, [pos])


  return problem.getSolutions()








  #return constraint.Problem().getSolutions()

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

# debug run
if __name__ == '__main__':
  if len(sys.argv) > 2:
    cmd = "{}({})".format(sys.argv[1], ",".join(sys.argv[2:]))
    print("debug run:", cmd)
    ret = eval(cmd)
    print("ret value:", ret)
    try:
      cnt = len(ret)
      print("ret count:", cnt)
    except TypeError:
      pass
  else:
    sys.stderr.write("Usage: {} <FUNCTION> <ARG>...\n".format(sys.argv[0]))
    sys.exit(1)

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
# vim:set et ts=2 sw=2:

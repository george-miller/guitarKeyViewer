
module Notes where

data Note = A | As | B | C | Cs | D | Ds | E | F | Fs | G | Gs deriving ( Read, Eq, Enum, Show, Ord, Bounded )

next :: Note -> Note
next Gs = A
next n = succ n

prev :: Note -> Note
prev A = Gs
prev n = pred n

whole :: Note -> Note
whole = next . next
half = next

majorScale :: Note -> [Note]
majorScale n = scanl ($ n) n [whole, whole, half, whole, whole, whole, half]

-- Like map and fold put together, it maps over a list of functions
mapfold :: [a -> a] -> a -> [a]
mapfold ( f:fs ) n = f n : mapfold fs (f n)
mapfold [] n = []

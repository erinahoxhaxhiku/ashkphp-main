import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Heart, Shield, Users, Award, ArrowRight, PawPrintIcon as Paw, CheckCircle } from "lucide-react"
import Link from "next/link"
import Image from "next/image"

export default function AdoptionPage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50">
      {/* Navigation */}
      <nav className="bg-white/95 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <Link href="/" className="flex items-center space-x-3">
              <div className="w-10 h-10 bg-gradient-to-br from-green-600 to-blue-600 rounded-full flex items-center justify-center">
                <Paw className="w-6 h-6 text-white" />
              </div>
              <span className="text-xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                Animal Shelter Kosovo
              </span>
            </Link>
            <div className="hidden md:flex items-center space-x-8">
              <Link href="/#about" className="text-gray-700 hover:text-green-600 transition-colors">
                About
              </Link>
              <Link href="/#animals" className="text-gray-700 hover:text-green-600 transition-colors">
                Animals
              </Link>
              <Link href="/volunteering" className="text-gray-700 hover:text-green-600 transition-colors">
                Volunteer
              </Link>
              <Link href="/">
                <Button variant="outline" className="border-green-600 text-green-600 hover:bg-green-50">
                  Back to Home
                </Button>
              </Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="relative py-20 lg:py-32 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-green-600/20 to-blue-600/20"></div>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center space-y-8">
            <Badge className="bg-green-100 text-green-800 hover:bg-green-200">🏠 Find Your Perfect Match</Badge>
            <h1 className="text-4xl lg:text-6xl font-bold leading-tight">
              <span className="bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                Give a Home
              </span>
              <br />
              to a Friend in Need
            </h1>
            <p className="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
              Find your perfect companion today. Every adoption saves a life and brings immeasurable joy to both you and
              your new furry friend.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Button
                size="lg"
                className="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700"
              >
                Start Adoption Process <ArrowRight className="ml-2 w-5 h-5" />
              </Button>
              <Button size="lg" variant="outline" className="border-green-600 text-green-600 hover:bg-green-50">
                Browse Animals
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* Statistics Section */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <Badge className="bg-blue-100 text-blue-800 hover:bg-blue-200 mb-4">📊 Our Impact</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold mb-4">
              Making a <span className="text-blue-600">Difference</span> Together
            </h2>
            <p className="text-xl text-gray-600">See how our community is changing lives, one adoption at a time.</p>
          </div>

          <div className="grid md:grid-cols-4 gap-8">
            <Card className="text-center border-0 shadow-lg">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                  <Heart className="w-8 h-8 text-white" />
                </div>
                <div className="text-3xl font-bold text-green-600 mb-2">2,400</div>
                <div className="text-gray-600">Animals Adopted Yearly</div>
              </CardContent>
            </Card>

            <Card className="text-center border-0 shadow-lg">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                  <Users className="w-8 h-8 text-white" />
                </div>
                <div className="text-3xl font-bold text-blue-600 mb-2">200</div>
                <div className="text-gray-600">Monthly Volunteers</div>
              </CardContent>
            </Card>

            <Card className="text-center border-0 shadow-lg">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                  <Award className="w-8 h-8 text-white" />
                </div>
                <div className="text-3xl font-bold text-purple-600 mb-2">70</div>
                <div className="text-gray-600">Weekly Helpers</div>
              </CardContent>
            </Card>

            <Card className="text-center border-0 shadow-lg">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                  <Shield className="w-8 h-8 text-white" />
                </div>
                <div className="text-3xl font-bold text-orange-600 mb-2">95%</div>
                <div className="text-gray-600">Success Rate</div>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Why Adopt Section */}
      <section className="py-20 bg-gradient-to-br from-gray-50 to-green-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <Badge className="bg-green-100 text-green-800 hover:bg-green-200 mb-4">💚 Why Choose Us</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold mb-4">
              Why Adopt <span className="text-green-600">From Us?</span>
            </h2>
            <p className="text-xl text-gray-600">
              We ensure every adoption is a perfect match for both you and your new companion.
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-8 text-center">
                <div className="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                  <Heart className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Save a Life</h3>
                <p className="text-gray-600 leading-relaxed">
                  Each adoption gives a homeless animal a second chance at life and opens up space for another animal in
                  need.
                </p>
              </CardContent>
            </Card>

            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-8 text-center">
                <div className="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                  <Shield className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Fully Vetted Pets</h3>
                <p className="text-gray-600 leading-relaxed">
                  All our animals are vaccinated, neutered, and health checked by qualified veterinarians before
                  adoption.
                </p>
              </CardContent>
            </Card>

            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-8 text-center">
                <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                  <Users className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Support Local</h3>
                <p className="text-gray-600 leading-relaxed">
                  By adopting locally, you're helping shelters in Kosovo thrive and continue their important work in the
                  community.
                </p>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Adoption Process */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <Badge className="bg-blue-100 text-blue-800 hover:bg-blue-200 mb-4">📋 Simple Process</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold mb-4">
              How to <span className="text-blue-600">Adopt</span>
            </h2>
            <p className="text-xl text-gray-600">
              Our adoption process is designed to ensure the best match for both you and your new pet.
            </p>
          </div>

          <div className="grid md:grid-cols-4 gap-8">
            {[
              {
                step: "1",
                title: "Browse Animals",
                description: "Look through our available animals and find one that catches your heart.",
              },
              {
                step: "2",
                title: "Submit Application",
                description: "Fill out our adoption application with your information and preferences.",
              },
              {
                step: "3",
                title: "Meet & Greet",
                description: "Visit the shelter to meet your potential new companion in person.",
              },
              {
                step: "4",
                title: "Take Home",
                description: "Complete the adoption process and welcome your new family member home!",
              },
            ].map((item, index) => (
              <div key={index} className="text-center">
                <div className="w-16 h-16 bg-gradient-to-br from-blue-600 to-green-600 rounded-full flex items-center justify-center mb-6 mx-auto text-white text-xl font-bold">
                  {item.step}
                </div>
                <h3 className="text-xl font-bold mb-4">{item.title}</h3>
                <p className="text-gray-600 leading-relaxed">{item.description}</p>
                {index < 3 && (
                  <div className="hidden md:block absolute top-8 left-full w-full">
                    <ArrowRight className="w-6 h-6 text-gray-400 mx-auto" />
                  </div>
                )}
              </div>
            ))}
          </div>

          <div className="text-center mt-12">
            <Button
              size="lg"
              className="bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700"
            >
              Start Your Adoption Journey <ArrowRight className="ml-2 w-5 h-5" />
            </Button>
          </div>
        </div>
      </section>

      {/* Success Stories */}
      <section className="py-20 bg-gradient-to-br from-blue-50 to-green-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <Badge className="bg-green-100 text-green-800 hover:bg-green-200 mb-4">❤️ Happy Endings</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold mb-4">
              Success <span className="text-green-600">Stories</span>
            </h2>
            <p className="text-xl text-gray-600">
              See how adoption has changed lives for both pets and their new families.
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                name: "Bella & The Johnson Family",
                story:
                  "Bella found her forever home with the Johnsons after 6 months at the shelter. Now she's the queen of the house!",
                image: "/placeholder.svg?height=300&width=400",
              },
              {
                name: "Max & Sarah",
                story:
                  "Max was a shy rescue dog who blossomed with Sarah's patience and love. They're now inseparable hiking buddies!",
                image: "/placeholder.svg?height=300&width=400",
              },
              {
                name: "Luna & The Petrovics",
                story:
                  "Luna the cat went from street life to luxury with the Petrovic family. She now rules her own cat tower kingdom!",
                image: "/placeholder.svg?height=300&width=400",
              },
            ].map((story, index) => (
              <Card
                key={index}
                className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg overflow-hidden"
              >
                <div className="relative">
                  <Image
                    src={story.image || "/placeholder.svg"}
                    alt={story.name}
                    width={400}
                    height={300}
                    className="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300"
                  />
                  <div className="absolute top-4 right-4">
                    <Badge className="bg-green-600 text-white">
                      <CheckCircle className="w-4 h-4 mr-1" />
                      Adopted
                    </Badge>
                  </div>
                </div>
                <CardContent className="p-6">
                  <h3 className="text-xl font-bold mb-3">{story.name}</h3>
                  <p className="text-gray-600 leading-relaxed">{story.story}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="space-y-8">
            <Badge className="bg-purple-100 text-purple-800 hover:bg-purple-200">🎉 Ready to Adopt?</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold">
              Your Perfect <span className="text-purple-600">Companion</span> is Waiting
            </h2>
            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
              Don't wait any longer. There's an animal at our shelter right now who would love nothing more than to be
              part of your family.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Button
                size="lg"
                className="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700"
              >
                See Available Animals <ArrowRight className="ml-2 w-5 h-5" />
              </Button>
              <Button size="lg" variant="outline" className="border-purple-600 text-purple-600 hover:bg-purple-50">
                Contact Us
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <p className="text-gray-400">&copy; 2025 Animal Shelter Kosovo. All rights reserved.</p>
        </div>
      </footer>
    </div>
  )
}
